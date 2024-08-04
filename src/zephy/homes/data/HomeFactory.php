<?php

namespace zephy\homes\data;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use zephy\homes\HomeSystem;
use zephy\homes\util\Utils;

class HomeFactory {
    use SingletonTrait;
    
    private array $homes = [];
    
    public function existsPlayer(Player $player): bool {
        return isset($this->homes[$player->getName()]);
    } 
    
    public function getHomes(Player $player): array {
        return $this->homes[$player->getName()];
    }
    
    public function getHome(string|Player $player, string $home): ?Home{
        if($player instanceof Player) {
            if (!isset($this->homes[$player->getName()])) return null;

            return $this->homes[$player->getName()][$home] ?? null;
        } else {
            return $this->homes[$player][$home] ?? null;
        } 
    }
    
    public function addHome(Player $player, string $home): void{
        $this->homes[$player->getName()][$home] = new Home($home);
    }
    
    public function deleteHome(Player $player, string $home): void {
        unset($this->homes[$player->getName()][$home]);
    }
    
    public function save(): void {
        @mkdir(HomeSystem::getInstance()->getDataFolder() . 'players');
        
        foreach ($this->homes as $name => $homes) {
            foreach($homes as $home) {
               $config = new Config(HomeSystem::getInstance()->getDataFolder() . 'players/' . $name . '.yml', Config::YAML);
               $config->set(
                     $home->getHomeName(), 
                     $home->data()
               );
               $config->save();
            } 
            
       } 
    }
    public function load(): void {
        $files = glob(HomeSystem::getInstance()->getDataFolder() . 'players/*.yml');
        
        foreach ($files as $file) {
            $name = basename($file, '.yml');
            $config = new Config(HomeSystem::getInstance()->getDataFolder() . 'players/' . $name . '.yml', Config::YAML);
            foreach($config->getAll() as $home => $data) {

               $this->addHome($name, $home);
               $homeEdit = $this->getHome($name, $home);
               $homeEdit->setDecorativeItem($data["item"]);
               $homeEdit->setPosition(Utils::getInstance()->stringToPosition($data["position"]));
           } 
     } 
  }
}
