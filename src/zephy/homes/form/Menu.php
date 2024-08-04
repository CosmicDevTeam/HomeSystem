<?php

namespace zephy\homes\form;

use muqsit\invmenu\InvMenu;
use zephy\homes\HomeSystem;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use zephy\homes\data\DataManager;
use pocketmine\utils\SingletonTrait;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\StringToItemParser;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use zephy\homes\data\HomeFactory;

class Menu {
    use SingletonTrait;
    public function __construct()
    {
        self::setInstance($this);
    }

    public function sendInventory(Player $player){
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("§gMy Homes");
        foreach(HomeFactory::getInstance()->getHomes($player) as $home){
            $itemname = $home->getDecorativeItem();
            $item = StringToItemParser::getInstance()->parse($itemname);
            $item->setCustomName($home->getHomeName());
            $item->setLore(["§aWorld : " . $home->getPosition()->getWorld()->getFolderName()]);
            $item->setNamedTag($item->getNamedTag()->setString("home", $home->getHomeName()));
            $menu->getInventory()->addItem($item);
        }
        $menu->setListener(fn(InvMenuTransaction $trans): InvMenuTransactionResult => self::transaction($trans));
        $menu->send($player);

    }
    public function transaction(InvMenuTransaction $trans): InvMenuTransactionResult{
        $player = $trans->getPlayer();
        $item = $trans->getItemClicked();
        if($name = $item->getNamedTag()->getString("home")){
            if(HomeFactory::getInstance()->getHome($player, $name) !== null){
                $player->teleport(HomeFactory::getInstance()->getHome($player, $name)->getPosition());
                $player->sendMessage(HomeSystem::PREFIX . "§aYou was teleported to your home succesfully");
                
                return $trans->discard();
            } else {
                return $trans->discard();
            }

        }

        return $trans->discard();
    }
}