<?php

namespace zephy\homes;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use zephy\homes\commands\HomeCommand;
use zephy\homes\data\HomeFactory;

class HomeSystem extends PluginBase {
    use SingletonTrait;
    const PREFIX = "§6(§gHomeSystem§6) §f";

    protected function onEnable(): void
    {
        self::setInstance($this);
        $this->getServer()->getCommandMap()->register("HomeSystem", new HomeCommand());
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
        HomeFactory::getInstance()->load();
    }

    protected function onDisable(): void
    {
        HomeFactory::getInstance()->save();
    }

}