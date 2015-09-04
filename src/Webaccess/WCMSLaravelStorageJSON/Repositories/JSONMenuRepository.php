<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use Webaccess\WCMSCore\Entities\Menu;
use Webaccess\WCMSCore\Repositories\MenuRepositoryInterface;

class JSONMenuRepository implements MenuRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'menus.json';
        $this->counter = 0;
        $this->menus = [];

        $this->loadFromJSON();
    }

    public function findByID($menuID)
    {
        foreach ($this->menus as $menu) {
            if ($menuID == $menu->getID()) {
                return $menu;
            }
        }

        return false;
    }

    public function findByIdentifier($menuIdentifier)
    {
        foreach ($this->menus as $menu) {
            if ($menuIdentifier == $menu->getIdentifier()) {
                return $menu;
            }
        }

        return false;
    }

    public function findAll($langID = null)
    {
        if ($langID) {
            $menus = [];
            foreach ($this->menus as $i => $menu) {
                if ($menu->getLangID() == $langID) {
                    $menus[]= $menu;
                }
            }

            return $menus;
        }

        return $this->menus;
    }

    public function createMenu(Menu $menu)
    {
        $this->counter++;
        $menu->setID($this->counter);
        $this->menus[]= $menu;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateMenu(Menu $menu)
    {
        foreach ($this->menus as $i => $menuJSON) {
            if ($menu->getID() == $menuJSON->getID()) {
                $this->menus[$i] = $menu;
            }
        }

        $this->writeToJSON();
    }

    public function deleteMenu($menuID)
    {
        foreach ($this->menus as $i => $menuJSON) {
            if ($menuJSON->getID() == $menuID) {
                unset($this->menus[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $menus = [];
        foreach ($this->menus as $menu) {
            $menus[]= [
                'id' => $menu->getID(),
                'name' => $menu->getName(),
                'identifier' => $menu->getIdentifier(),
                'lang_id' => $menu->getLangID(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $menus]));
    }

    private function loadFromJSON()
    {
        if (!is_dir($this->jsonFolder)) {
            mkdir($this->jsonFolder);
        }

        if (!file_exists($this->json)) {
            file_put_contents($this->json, null);
        }

        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $menus = $data[1];

            if (is_array($menus) && sizeof($menus) > 0) {
                foreach ($menus as $menuData) {
                    $menu = new Menu();
                    foreach ($menuData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $menu->$method($value);
                    }

                    $this->menus[] = $menu;
                }
            }
        }
    }
}