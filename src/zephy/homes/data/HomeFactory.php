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
    public function getHomes(Player $player): array {
        return $this->homes[$player->getName()];
    }
    public function getHome(Player $player, string $home): ?Home{
        return $this->homes[$player->getName()][$home] ?? null;
    }
    public function addHome(Player $player, string $home): void{
        $this->homes[$player->getName()][$home] = new Home($home);
    }
    public function deleteHome(Player $player, string $home): void {
        unset($this->homes[$player->getName()][$home]);
    }
    public function save(): void {
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . "homes.json");
        foreach($this->homes as $name => $homes){
            $config->set($name, $homes->data());
            $config->save();
        }
    }

    public function load(): void {
        $config = new Config(HomeSystem::getInstance()->getDataFolder() . "homes.json");
        foreach($config->getAll(true) as $players){
            $data = $config->get($players);
            $this->homes[$data][$data[0]] = new Home($data[0]);
            $home = $this->getHome($data, $data[0]);
            $home->setDecorativeItem($data["item"]);
            $home->setPosition(Utils::getInstance()->stringToPosition($data["position"]));
        }
    }
}