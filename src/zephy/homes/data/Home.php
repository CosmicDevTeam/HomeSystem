<?php

namespace zephy\homes\data;

use pocketmine\world\Position;
use zephy\homes\util\Utils;

class Home {
    private string $decorativeItem = "chest";
    private Position $position;
    public function __construct(
        private string $home
    )
    {
        
    }

    public function setDecorativeItem(string $vanilla): void {
        $this->decorativeItem = $vanilla;
    }
    public function getDecorativeItem(): string {
        return $this->decorativeItem;
    }
    
    public function getPosition(): Position {
        return $this->position;
    }
    public function setPosition(Position $pos): void {
        $this->position = $pos;
    }
    public function getHomeName(): string {
        return $this->home;
    }

    public function data(): array {
        return [
                
                "position" => Utils::getInstance()->positionToString($this->getPosition()),
                "item" => $this->getDecorativeItem()
            
            ];
    }
}