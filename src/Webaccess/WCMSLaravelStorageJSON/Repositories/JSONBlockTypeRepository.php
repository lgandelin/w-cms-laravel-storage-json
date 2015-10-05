<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use Webaccess\WCMSCore\Entities\BlockType;
use Webaccess\WCMSCore\Repositories\BlockTypeRepositoryInterface;

class JSONBlockTypeRepository implements BlockTypeRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'block_types.json';
        $this->counter = 0;
        $this->blockTypes = [];

        $this->loadFromJSON();
    }

    public function findAll()
    {
        return $this->blockTypes;
    }

    public function findByCode($code) {
        foreach ($this->blockTypes as $blockType) {
            if ($code == $blockType->getCode()) {
                return $blockType;
            }
        }

        return false;
    }

    public function createBlockType(BlockType $blockType)
    {
        $this->counter++;
        $blockType->setID($this->counter);
        $this->blockTypes[]= $blockType;
        $this->writeToJSON();

        return $this->counter;
    }

    public function deleteBlockType($blockTypeID)
    {
        foreach ($this->blockTypes as $i => $blockTypeJSON) {
            if ($blockTypeJSON->getID() == $blockTypeID) {
                unset($this->blockTypes[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $blockTypes = [];
        foreach ($this->blockTypes as $blockType) {
            $blockTypes[]= [
                'id' => $blockType->getID(),
                'code' => $blockType->getCode(),
                'name' => $blockType->getName(),
                'entity' => $blockType->getEntity(),
                'back_controller' => $blockType->getBackController(),
                'back_view' => $blockType->getBackView(),
                'front_controller' => $blockType->getFrontController(),
                'front_view' => $blockType->getFrontView(),
                'order' => $blockType->getOrder(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $blockTypes]));
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
            $blockTypes = $data[1];

            if (is_array($blockTypes) && sizeof($blockTypes) > 0) {
                foreach ($blockTypes as $blockTypeData) {
                    $blockType = new BlockType();
                    foreach ($blockTypeData as $property => $value) {
                        $method = 'set' . self::snakeToCamel($property);
                        $blockType->$method($value);
                    }

                    $this->blockTypes[] = $blockType;
                }
            }
        }
    }

    private static function snakeToCamel($property)
    {
        return ucfirst(str_replace('_', '', $property));
    }
}