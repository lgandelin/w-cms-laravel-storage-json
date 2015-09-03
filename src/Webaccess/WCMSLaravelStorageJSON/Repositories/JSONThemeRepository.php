<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use Webaccess\WCMSCore\Entities\Theme;
use Webaccess\WCMSCore\Repositories\ThemeRepositoryInterface;

class JSONThemeRepository implements ThemeRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'themes.json';
        $this->counter = 0;
        $this->themes = [];

        $this->loadFromJSON();
    }

    public function findByID($themeID)
    {
        foreach ($this->themes as $theme) {
            if ($themeID == $theme->getID()) {
                return $theme;
            }
        }

        return false;
    }

    public function findAll()
    {
        return $this->themes;
    }

    public function findSelectedThemeIdentifier()
    {
        foreach ($this->themes as $theme) {
            if ($theme->getIsSelected()) {
                return $theme->getIdentifier();
            }
        }

        return false;
    }

    public function createTheme(Theme $theme)
    {
        $this->counter++;
        $theme->setID($this->counter);
        $this->themes[]= $theme;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateTheme(Theme $theme)
    {
        foreach ($this->themes as $i => $themeJSON) {
            if ($theme->getID() == $themeJSON->getID()) {
                $this->themes[$i] = $theme;
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $themes = [];
        foreach ($this->themes as $theme) {
            $themes[]= [
                'id' => $theme->getID(),
                'identifier' => $theme->getIdentifier(),
                'is_selected' => $theme->getIsSelected(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $themes]));
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
            $themes = $data[1];

            if (is_array($themes) && sizeof($themes) > 0) {
                foreach ($themes as $themeData) {
                    $theme = new Theme();
                    foreach ($themeData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $theme->$method($value);
                    }

                    $this->themes[] = $theme;
                }
            }
        }
    }
}