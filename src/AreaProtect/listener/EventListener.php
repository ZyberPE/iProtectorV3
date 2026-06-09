<?php

declare(strict_types=1);

namespace AreaProtect\listener;

use AreaProtect\Main;

use pocketmine\event\Listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\player\Player;

final class EventListener implements Listener{

    public function onBreak(BlockBreakEvent $event) : void{
        $player = $event->getPlayer();

        if($player->hasPermission("areaprotect.bypass")){
            return;
        }

        $area = Main::getInstance()
            ->getAreaManager()
            ->getAreaAt($event->getBlock()->getPosition());

        if($area !== null && !$area->getFlag("edit")){
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event) : void{
        $player = $event->getPlayer();

        if($player->hasPermission("areaprotect.bypass")){
            return;
        }

        foreach($event->getTransaction()->getBlocks() as $blockData){

            $block = $blockData[3];

            $area = Main::getInstance()
                ->getAreaManager()
                ->getAreaAt($block->getPosition());

            if($area !== null && !$area->getFlag("edit")){
                $event->cancel();
                return;
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event) : void{

        $player = $event->getPlayer();

        if($player->hasPermission("areaprotect.bypass")){
            return;
        }

        $area = Main::getInstance()
            ->getAreaManager()
            ->getAreaAt($event->getBlock()->getPosition());

        if($area !== null && !$area->getFlag("interact")){
            $event->cancel();
        }
    }

    public function onDamage(EntityDamageEvent $event) : void{

        $entity = $event->getEntity();

        if(!$entity instanceof Player){
            return;
        }

        $area = Main::getInstance()
            ->getAreaManager()
            ->getAreaAt($entity->getPosition());

        if($area !== null && $area->getFlag("god")){
            $event->cancel();
        }
    }

    public function onPvP(EntityDamageByEntityEvent $event) : void{

        $victim = $event->getEntity();
        $damager = $event->getDamager();

        if(
            !$victim instanceof Player ||
            !$damager instanceof Player
        ){
            return;
        }

        $area = Main::getInstance()
            ->getAreaManager()
            ->getAreaAt($victim->getPosition());

        if($area !== null && !$area->getFlag("pvp")){
            $event->cancel();
        }
    }
}
