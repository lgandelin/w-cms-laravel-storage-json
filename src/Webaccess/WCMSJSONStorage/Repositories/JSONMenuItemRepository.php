<?php

namespace Webaccess\WCMSJSONStorage\Repositories;

use CMS\Entities\MenuItem;
use CMS\Repositories\MenuItemRepositoryInterface;

class JSONMenuItemRepository implements MenuItemRepositoryInterface
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/menu_items.json';
        $this->counter = 1;
        $this->menuItems = [];

        $this->loadFromJSON();
    }

    public function findByID($menuItemID)
    {
        foreach ($this->menuItems as $menuItem) {
            if ($menuItemID == $menuItem->getID()) {
                return $menuItem;
            }
        }

        return false;
    }

    public function findByMenuID($menuID)
    {
        $menuItems = [];
        foreach ($this->menuItems as $menuItem) {
            if ($menuID == $menuItem->getMenuID()) {
                $menuItems[]= $menuItem;
            }
        }

        return $menuItems;
    }

    public function findAll($langID = null)
    {
        if ($langID) {
            $menuItems = [];
            foreach ($this->menuItems as $i => $menuItem) {
                if ($menuItem->getLangID() == $langID) {
                    $menuItems[]= $menuItem;
                }
            }

            return $menuItems;
        }

        return $this->menuItems;
    }

    public function createMenuItem(MenuItem $menuItem)
    {
        $this->counter++;
        $menuItem->setID($this->counter);
        $this->menuItems[]= $menuItem;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateMenuItem(MenuItem $menuItem)
    {
        foreach ($this->menuItems as $i => $menuItemJSON) {
            if ($menuItem->getID() == $menuItemJSON->getID()) {
                $this->menuItems[$i] = $menuItem;
            }
        }

        $this->writeToJSON();
    }

    public function deleteMenuItem($menuItemID)
    {
        foreach ($this->menuItems as $i => $menuItemJSON) {
            if ($menuItemJSON->getID() == $menuItemID) {
                unset($this->menuItems[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $menuItems = [];
        foreach ($this->menuItems as $menuItem) {
            $menuItems[]= [
                'id' => $menuItem->getID(),
                'label' => $menuItem->getLabel(),
                'order' => $menuItem->getOrder(),
                'page_id' => $menuItem->getPageID(),
                'external_url' => $menuItem->getExternalURL(),
                'class' => $menuItem->getClass(),
                'menu_id' => $menuItem->getMenuID(),
                'display' => $menuItem->getDisplay(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $menuItems]));
    }

    private function loadFromJSON()
    {
        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $menuItems = $data[1];

            if (is_array($menuItems) && sizeof($menuItems) > 0) {
                foreach ($menuItems as $menuItemData) {
                    $menuItem = new MenuItem();
                    foreach ($menuItemData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $menuItem->$method($value);
                    }

                    $this->menuItems[] = $menuItem;
                }
            }
        }
    }
}