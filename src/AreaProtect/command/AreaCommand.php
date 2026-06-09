<?php

declare(strict_types=1);

namespace AreaProtect\command;

use AreaProtect\Main;
use AreaProtect\SelectionManager;
use AreaProtect\area\Area;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class AreaCommand extends Command{

    public function __construct(){
        parent::__construct(
            "area",
            "Area protection command"
        );

        $this->setPermission("areaprotect.command");
    }

    public function execute(
        CommandSender $sender,
        string $commandLabel,
        array $args
    ) : bool{

        if(!$sender instanceof Player){
            return true;
        }

        if(count($args) === 0){
            $sender->sendMessage("§e/area pos1");
            $sender->sendMessage("§e/area pos2");
            $sender->sendMessage("§e/area create <name>");
            $sender->sendMessage("§e/area delete <name>");
            $sender->sendMessage("§e/area list");
            return true;
        }

        switch(strtolower($args[0])){

            case "pos1":
                SelectionManager::setPos1(
                    $sender->getName(),
                    $sender->getPosition()
                );

                $sender->sendMessage("§aPosition 1 set.");
                return true;

            case "pos2":
                SelectionManager::setPos2(
                    $sender->getName(),
                    $sender->getPosition()
                );

                $sender->sendMessage("§aPosition 2 set.");
                return true;

            case "create":

                if(!isset($args[1])){
                    return true;
                }

                $p1 = SelectionManager::getPos1(
                    $sender->getName()
                );

                $p2 = SelectionManager::getPos2(
                    $sender->getName()
                );

                if($p1 === null || $p2 === null){
                    $sender->sendMessage("§cSet pos1 and pos2 first.");
                    return true;
                }

                $area = new Area(
                    $args[1],
                    $sender->getWorld()->getFolderName(),

                    min($p1->getFloorX(), $p2->getFloorX()),
                    min($p1->getFloorY(), $p2->getFloorY()),
                    min($p1->getFloorZ(), $p2->getFloorZ()),

                    max($p1->getFloorX(), $p2->getFloorX()),
                    max($p1->getFloorY(), $p2->getFloorY()),
                    max($p1->getFloorZ(), $p2->getFloorZ()),

                    [
                        "edit" => false,
                        "pvp" => false,
                        "interact" => false,
                        "god" => false
                    ]
                );

                Main::getInstance()
                    ->getAreaManager()
                    ->addArea($area);

                $sender->sendMessage(
                    "§aArea created."
                );

                return true;

            case "delete":

                if(!isset($args[1])){
                    return true;
                }

                if(
                    Main::getInstance()
                        ->getAreaManager()
                        ->removeArea($args[1])
                ){
                    $sender->sendMessage("§aArea deleted.");
                }

                return true;

            case "list":

                foreach(
                    Main::getInstance()
                        ->getAreaManager()
                        ->getAreas() as $area
                ){
                    $sender->sendMessage(
                        "§e- " . $area->getName()
                    );
                }

                return true;
        }

        return true;
    }
}
