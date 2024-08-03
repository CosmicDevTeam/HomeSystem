<?php

namespace zephy\homes\util;

use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;

class Utils {
    use SingletonTrait;

    public function positionToString(Position $position): string {
        return $position->x . ":" . $position->y . ":" . $position->z . ":" . $position->getWorld()->getFolderName();
    }

    public function stringToPosition(string $position): ?Position {
        $data = explode(":", $position);
        if(\count($data) < 4){
           return null;
        }
        return new Position($data[0], $data[1], $data[2], Server::getInstance()->getWorldManager()->getWorldByName($data[3]));
    }
}