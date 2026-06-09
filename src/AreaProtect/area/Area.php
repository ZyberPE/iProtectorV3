<?php

declare(strict_types=1);

namespace AreaProtect\area;

use pocketmine\world\Position;

final class Area{

    public function __construct(
        private string $name,
        private string $world,
        private int $minX,
        private int $minY,
        private int $minZ,
        private int $maxX,
        private int $maxY,
        private int $maxZ,
        private array $flags = []
    ){}

    public function getName() : string{
        return $this->name;
    }

    public function getWorld() : string{
        return $this->world;
    }

    public function contains(Position $pos) : bool{
        if($pos->getWorld()->getFolderName() !== $this->world){
            return false;
        }

        return
            $pos->getFloorX() >= $this->minX &&
            $pos->getFloorX() <= $this->maxX &&
            $pos->getFloorY() >= $this->minY &&
            $pos->getFloorY() <= $this->maxY &&
            $pos->getFloorZ() >= $this->minZ &&
            $pos->getFloorZ() <= $this->maxZ;
    }

    public function getFlag(string $flag) : bool{
        return $this->flags[$flag] ?? false;
    }

    public function setFlag(string $flag, bool $value) : void{
        $this->flags[$flag] = $value;
    }

    public function getFlags() : array{
        return $this->flags;
    }

    public function toArray() : array{
        return [
            "world" => $this->world,
            "minX" => $this->minX,
            "minY" => $this->minY,
            "minZ" => $this->minZ,
            "maxX" => $this->maxX,
            "maxY" => $this->maxY,
            "maxZ" => $this->maxZ,
            "flags" => $this->flags
        ];
    }
}
