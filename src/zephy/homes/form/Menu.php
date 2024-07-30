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

class Menu {
    use SingletonTrait;
    public function __construct()
    {
        self::setInstance($this);
    }

    public function sendInventory(Player $player){
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . $player->getName() . ".yml", Config::YAML);
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("§gMy Homes");
        foreach(DataManager::getInstance()->getAllHomes($player) as $home){
            $itemname = $config->get($home)["item"];
            $item = StringToItemParser::getInstance()->parse($itemname);
            $item->setCustomName($home);
            $item->setLore(["§aWorld : " . $config->get($home)["world"]]);
            $item->setNamedTag($item->getNamedTag()->setString("home", $home));
            $menu->getInventory()->addItem($item);
        }
        $menu->setListener(fn(InvMenuTransaction $trans): InvMenuTransactionResult => self::transaction($trans));
        $menu->send($player);

    }
    public function transaction(InvMenuTransaction $trans): InvMenuTransactionResult{
        $player = $trans->getPlayer();
        $item = $trans->getItemClicked();
        if($name = $item->getNamedTag()->getString("home")){
            if(DataManager::getInstance()->exists($player, $name)){
                $player->teleport(DataManager::getInstance()->exactCoordinates($player, $name));
                $player->sendMessage(HomeSystem::PREFIX . "§aYou was teleported to your home succesfully");
                
                return $trans->discard();
            } else {
                return $trans->discard();
            }

        }

        return $trans->discard();
    }
}