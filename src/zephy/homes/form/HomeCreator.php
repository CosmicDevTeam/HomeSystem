<?php

namespace zephy\homes\form;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use zephy\homes\data\DataManager;
use zephy\homes\HomeSystem;

class HomeCreator extends CustomForm {
 public function __construct()
 {
    parent::__construct(function(Player $player, ? array $data){

        if(is_null($data[0])){
            $player->sendMessage(HomeSystem::PREFIX . "§4You must place the name of the home");
            return;
        }

        if(DataManager::getInstance()->exists($player, $data[0])){
            $player->sendMessage(HomeSystem::PREFIX . "This home already exists");
            return;
        }

        DataManager::getInstance()->createHome($player, $data[0]);
        $player->sendMessage(HomeSystem::PREFIX . "§aYou created your home succesfully");

    });
    $this->setTitle("Home Creator");
    $this->addInput("Name", "", null);
 }

}