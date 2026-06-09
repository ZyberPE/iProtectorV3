<?php

declare(strict_types=1);

namespace AreaProtect;

use pocketmine\world\Position;

final class SelectionManager{

    /** @var Position[] */
    private static array $pos1 = [];

    /** @var Position[] */
    private static array $pos2 = [];

    public static function setPos1(string $player, Position $position) : void{
        self::$pos1[strtolower($player)] = $position;
    }

    public static function setPos2(string $player, Position $position) : void{
        self::$pos2[strtolower($player)] = $position;
    }

    public static function getPos1(string $player) : ?Position{
        return self::$pos1[strtolower($player)] ?? null;
    }

    public static function getPos2(string $player) : ?Position{
        return self::$pos2[strtolower($player)] ?? null;
    }
}
