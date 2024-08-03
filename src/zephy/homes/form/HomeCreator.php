<?php

namespace zephy\homes\form;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use zephy\homes\data\HomeFactory;
use zephy\homes\HomeSystem;

class HomeCreator extends CustomForm {
 public function __construct()
 {
    parent::__construct(function(Player $player, ? array $data){

        if(is_null($data[0])){
            $player->sendMessage(HomeSystem::PREFIX . "§4You must place the name of the home");
            return;
        }

        if(HomeFactory::getInstance()->getHome($player, $data[0]) === null){
            $player->sendMessage(HomeSystem::PREFIX . "This home already exists");
            return;
        }

        HomeFactory::getInstance()->addHome($player, $data[0]);
        HomeFactory::getInstance()->getHome($player, $data[0])->setPosition($player->getPosition());
        $player->sendMessage(HomeSystem::PREFIX . "§aYou created your home succesfully");

    });
    $this->setTitle("Home Creator");
    $this->addInput("Name", "", null);
 }

}