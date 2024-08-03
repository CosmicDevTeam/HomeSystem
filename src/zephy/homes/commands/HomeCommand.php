<?php

namespace zephy\homes\commands;

use zephy\homes\form\Menu;
use zephy\homes\HomeSystem;
use pocketmine\player\Player;
use pocketmine\command\Command;
use zephy\homes\data\DataManager;
use zephy\homes\form\HomeCreator;
use zephy\homes\form\ItemsSelector;
use pocketmine\command\CommandSender;
use zephy\homes\data\HomeFactory;

class HomeCommand extends Command {
    public function __construct()
    {
        parent::__construct("home", "", null, ["hs", "homesystem"]);
        $this->setPermission("home.default");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            return;
        }
        if(!isset($args[0])){
            Menu::getInstance()->sendInventory($sender);
            return;
        }
        switch($args[0]){

            case "create":
            if(HomeFactory::getInstance()->existsPlayer($sender)) {
                 if(count(HomeFactory::getInstance()->getHomes($sender)) >= 26){
                $sender->sendMessage(HomeSystem::PREFIX . "§4You have the maximum number of houses remove some");
                return;
                }
            } 
                $sender->sendForm(new HomeCreator());
                return;
            break;

            case "delete":

                if(!isset($args[1])){
                    $sender->sendMessage(HomeSystem::PREFIX . "§4You must place the name of the home");
                    return;
                }
                if(HomeFactory::getInstance()->getHome($sender, $args[1]) === null){
                    $sender->sendMessage(HomeSystem::PREFIX . "§4This home dont exist");
                    return;
                }
                HomeFactory::getInstance()->deleteHome($sender, $args[1]);
                $sender->sendMessage(HomeSystem::PREFIX . "§aYour home was deleted succesfully");
                
            break;

            case "changeicon":
                if(!isset($args[1])){
                    $sender->sendMessage(HomeSystem::PREFIX . "§4You must place the name of the home");
                    return;
                }
                if(HomeFactory::getInstance()->getHome($sender, $args[1]) === null){
                    $sender->sendMessage(HomeSystem::PREFIX . "§4This home dont exist");
                    return;
                }
                ItemsSelector::getInstance()->sendInventory($sender, $args[1]);
            break;


        }
    }
}
