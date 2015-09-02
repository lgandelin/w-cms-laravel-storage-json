<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use CMS\Entities\Lang;
use CMS\Repositories\LangRepositoryInterface;

class JSONLangRepository implements LangRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'langs.json';
        $this->counter = 0;
        $this->langs = [];

        $this->loadFromJSON();
    }

    public function findByID($langID)
    {
        foreach ($this->langs as $lang) {
            if ($langID == $lang->getID()) {
                return $lang;
            }
        }

        return false;
    }

    public function findDefautLangID()
    {
        foreach ($this->langs as $lang) {
            if ($lang->getIsDefault()) {
                return $lang->getID();
            }
        }

        return false;
    }

    public function findAll()
    {
        return $this->langs;
    }

    public function createLang(Lang $lang)
    {
        $this->counter++;
        $lang->setID($this->counter);
        $this->langs[]= $lang;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateLang(Lang $lang)
    {
        foreach ($this->langs as $i => $langJSON) {
            if ($lang->getID() == $langJSON->getID()) {
                $this->langs[$i] = $lang;
            }
        }

        $this->writeToJSON();
    }

    public function deleteLang($langID)
    {
        foreach ($this->langs as $i => $langJSON) {
            if ($langJSON->getID() == $langID) {
                unset($this->langs[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $langs = [];
        foreach ($this->langs as $lang) {
            $langs[]= [
                'id' => $lang->getID(),
                'name' => $lang->getName(),
                'prefix' => $lang->getPrefix(),
                'code' => $lang->getCode(),
                'is_default' => $lang->getIsDefault(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $langs]));
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
            $langs = $data[1];

            if (is_array($langs) && sizeof($langs) > 0) {
                foreach ($langs as $langData) {
                    $lang = new Lang();
                    $lang->setID($langData['id']);
                    $lang->setName($langData['name']);
                    $lang->setPrefix($langData['prefix']);
                    $lang->setCode($langData['code']);
                    $lang->setIsDefault($langData['is_default']);

                    $this->langs[] = $lang;
                }
            }
        }
    }
}