<?php

declare(strict_types=1);

namespace AreaProtect\area;

use AreaProtect\Main;
use pocketmine\utils\Config;
use pocketmine\world\Position;

final class AreaManager{

    /** @var Area[] */
    private array $areas = [];

    private Config $config;

    public function __construct(
        private Main $plugin
    ){
        $this->config = new Config(
            $plugin->getDataFolder() . "areas.yml",
            Config::YAML
        );

        $this->load();
    }

    private function load() : void{
        foreach($this->config->getAll() as $name => $data){

            $this->areas[strtolower($name)] = new Area(
                $name,
                $data["world"],
                $data["minX"],
                $data["minY"],
                $data["minZ"],
                $data["maxX"],
                $data["maxY"],
                $data["maxZ"],
                $data["flags"] ?? []
            );
        }
    }

    public function save() : void{
        $data = [];

        foreach($this->areas as $area){
            $data[$area->getName()] = $area->toArray();
        }

        $this->config->setAll($data);
        $this->config->save();
    }

    public function addArea(Area $area) : void{
        $this->areas[strtolower($area->getName())] = $area;
        $this->save();
    }

    public function removeArea(string $name) : bool{
        $key = strtolower($name);

        if(!isset($this->areas[$key])){
            return false;
        }

        unset($this->areas[$key]);
        $this->save();

        return true;
    }

    public function getArea(string $name) : ?Area{
        return $this->areas[strtolower($name)] ?? null;
    }

    public function getAreaAt(Position $position) : ?Area{
        foreach($this->areas as $area){
            if($area->contains($position)){
                return $area;
            }
        }

        return null;
    }

    public function getAreas() : array{
        return $this->areas;
    }
}
