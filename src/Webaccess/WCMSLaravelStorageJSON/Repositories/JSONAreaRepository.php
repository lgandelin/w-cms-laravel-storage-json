<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use CMS\Entities\Area;
use CMS\Repositories\AreaRepositoryInterface;

class JSONAreaRepository implements AreaRepositoryInterface
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/areas.json';
        $this->counter = 1;
        $this->areas = [];

        $this->loadFromJSON();
    }

    public function findByID($areaID)
    {
        foreach ($this->areas as $area) {
            if ($areaID == $area->getID()) {
                return $area;
            }
        }

        return false;
    }

    public function findByPageID($pageID)
    {
        $areas = [];
        foreach ($this->areas as $area) {
            if ($pageID == $area->getPageID()) {
                $areas[]= $area;
            }
        }

        usort($areas, function($a, $b) {
            return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
        });

        return $areas;
    }

    public function findAll()
    {
        return $this->areas;
    }

    public function findChildAreas($areaID)
    {
        return [];
    }

    public function createArea(Area $area)
    {
        $this->counter++;
        $area->setID($this->counter);
        $this->areas[]= $area;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateArea(Area $area)
    {
        foreach ($this->areas as $i => $areaJSON) {
            if ($area->getID() == $areaJSON->getID()) {
                $this->areas[$i] = $area;
            }
        }

        $this->writeToJSON();
    }

    public function deleteArea($areaID)
    {
        foreach ($this->areas as $i => $areaJSON) {
            if ($areaJSON->getID() == $areaID) {
                unset($this->areas[$i]);
            }
        }

        $this->writeToJSON();
    }
    
    private function writeToJSON()
    {
        $areas = [];
        foreach ($this->areas as $area) {
            $areas[]= [
                'id' => $area->getID(),
                'name' => $area->getName(),
                'width' => $area->getWidth(),
                'height' => $area->getHeight(),
                'class' => $area->getClass(),
                'order' => $area->getOrder(),
                'display' => $area->getDisplay(),
                'page_id' => $area->getPageID(),
            ];
        }
        file_put_contents($this->json, json_encode([$this->counter, $areas]));
    }

    private function loadFromJSON()
    {
        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $areas = $data[1];

            if (is_array($areas) && sizeof($areas) > 0) {
                foreach ($areas as $areaData) {
                    $area = new Area();
                    $area->setID($areaData['id']);
                    $area->setName($areaData['name']);
                    $area->setWidth($areaData['width']);
                    $area->setHeight($areaData['height']);
                    $area->setClass($areaData['class']);
                    $area->setOrder($areaData['order']);
                    $area->setDisplay($areaData['display']);
                    $area->setPageID($areaData['page_id']);

                    $this->areas[] = $area;
                }
            }
        }
    }
}
