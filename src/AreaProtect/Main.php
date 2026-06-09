<?php

declare(strict_types=1);

namespace AreaProtect;
use AreaProtect\command\AreaCommand;
use pocketmine\plugin\PluginBase;
use AreaProtect\area\AreaManager;

final class Main extends PluginBase{

    private static Main $instance;
    private AreaManager $areaManager;

    public static function getInstance() : self{
        return self::$instance;
    }

    protected function onEnable() : void{
        self::$instance = $this;

        @mkdir($this->getDataFolder());

        $this->saveResource("areas.yml");

        $this->areaManager = new AreaManager($this);

        $this->getServer()->getPluginManager()->registerEvents(
            new \AreaProtect\listener\EventListener(),
            $this
        );
    }

    public function getAreaManager() : AreaManager{
        return $this->areaManager;
    }
}
