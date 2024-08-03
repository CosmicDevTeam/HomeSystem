<?php

namespace zephy\homes\form;

use muqsit\invmenu\InvMenu;
use zephy\homes\HomeSystem;
use pocketmine\player\Player;
use pocketmine\item\VanillaItems;
use zephy\homes\data\HomeFactory;
use pocketmine\utils\SingletonTrait;
use muqsit\invmenu\type\InvMenuTypeIds;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;

class ItemsSelector {
   use SingletonTrait;
   public function __construct()
   {
    self::setInstance($this);
   }

   public function sendInventory(Player $player, string $name){
    $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
    $menu->setName("Item Selector");
    $items = VanillaItems::getAll();
		for($i = 0, $max = $menu->getInventory()->getSize(); $i < $max; $i++){
			$menu->getInventory()->setItem($i, $items[array_rand($items)]);
		}
    $menu->setListener(fn(InvMenuTransaction $trans): InvMenuTransactionResult => self::transaction($trans, $name));
    $menu->send($player);
   }
   public function transaction(InvMenuTransaction $trans, string $name): InvMenuTransactionResult {
    $player = $trans->getPlayer();
    $item = $trans->getItemClicked();
    $vanilla = $item->getVanillaName();
    if(HomeFactory::getInstance()->getHome($player, $name) === null){
        $player->sendMessage(HomeSystem::PREFIX . "§4This home dont exist");
        return $trans->discard();
    }
   
    HomeFactory::getInstance()->getHome($player, $name)->setDecorativeItem($vanilla);
    $player->sendMessage(HomeSystem::PREFIX . "§aYou home icon is now setted to " . $vanilla);

    return $trans->discard();

    

   }
}