<?php

namespace zephy\homes\data;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;
use zephy\homes\HomeSystem;

class DataManager {
    use SingletonTrait; 
    public function __construct()
    {
        self::setInstance($this);
    }
    public function createHome(Player $player, string $name){
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . $player->getName() . ".yml", Config::YAML);
        $config->set($name, [
            "item" => "chest",
            "x" => $player->getPosition()->getX(),
            "y" => $player->getPosition()->getY(),
            "z" => $player->getPosition()->getZ(),
            "world" => $player->getWorld()->getFolderName()
        ]);
        $config->save();

    }
    public function setItem(Player $player, string $name, string $item)
    {
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . $player->getName() . ".yml", Config::YAML);
        $config->set($name, [
            "item" => $item,
            "x" => $config->get($name)["x"],
            "y" => $config->get($name)["y"],
            "z" => $config->get($name)["z"],
            "world" => $config->get($name)["world"]
        ]);
        $config->save();
    }


    public function removeHome(Player $player, string $name){

        $config = new Config(HomeSystem::getInstance()->getDataFolder() . $player->getName() . ".yml", Config::YAML);
        $config->remove($name);

        $config->save();
    }
    public function exists(Player $player, string $name){
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . $player->getName() . ".yml", Config::YAML);

        return $config->exists($name);
 
    }
    public function getAllHomes(Player $player): array {
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . $player->getName() . ".yml", Config::YAML);
        return $config->getAll(true);

     }
    public function exactCoordinates(Player $player, string $name) {
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . $player->getName() . ".yml", Config::YAML);
        $world = Server::getInstance()->getWorldManager()->getWorldByName($config->get($name)["world"]);
        return new Position($config->get($name)["x"], $config->get($name)["y"], $config->get($name)["z"], $world);
    }
    
}