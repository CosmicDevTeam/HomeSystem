<?php

namespace zephy\homes\form;

use muqsit\invmenu\InvMenu;
use zephy\homes\HomeSystem;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\item\VanillaItems;
use zephy\homes\data\DataManager;
use pocketmine\utils\SingletonTrait;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\StringToItemParser;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\block\VanillaBlocks;

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
    if(!DataManager::getInstance()->exists($player, $name)){
        $player->sendMessage(HomeSystem::PREFIX . "§4This home dont exist");
        return $trans->discard();
    }
   
    DataManager::getInstance()->setItem($player, $name, $vanilla);
    $player->sendMessage(HomeSystem::PREFIX . "§aYou home icon is now setted to " . $vanilla);

    return $trans->discard();

    

   }
}