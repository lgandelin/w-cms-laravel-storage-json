<?php

namespace Webaccess\WCMSJSONStorage\Repositories;

use CMS\Entities\BlockType;

class JSONBlockTypeRepository
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/block_types.json';
        $this->counter = 1;
        $this->blockTypes = [];

        $this->loadFromJSON();
    }

    public function findAll($structure = false)
    {
        if ($structure) {
            $blockTypes = [];

            foreach ($this->blockTypes as $blockType) {
                $blockTypes[]= $blockType->toStructure();
            }

            return $blockTypes;
        }

        return $this->blockTypes;
    }

    public function createBlockType(BlockType $blockType)
    {
        $this->counter++;
        $blockType->setID($this->counter);
        $this->blockTypes[]= $blockType;
        $this->writeToJSON();

        return $this->counter;
    }

    public function getBlockTypeByCode($code, $structure = false) {
        foreach ($this->blockTypes as $blockType) {
            if ($code == $blockType->getCode()) {
                return ($structure) ? $blockType->toStructure() : $blockType;
            }
        }

        return false;
    }

    private function writeToJSON()
    {
        $blockTypes = [];
        foreach ($this->blockTypes as $blockType) {
            $blockTypes[]= [
                'id' => $blockType->getID(),
                'code' => $blockType->getCode(),
                'name' => $blockType->getName(),
                'content_view' => $blockType->getContentView(),
                'front_view' => $blockType->getFrontView(),
                'order' => $blockType->getOrder(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $blockTypes]));
    }

    private function loadFromJSON()
    {
        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $blockTypes = $data[1];

            if (is_array($blockTypes) && sizeof($blockTypes) > 0) {
                foreach ($blockTypes as $blockTypeData) {
                    $blockType = new BlockType();
                    foreach ($blockTypeData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $blockType->$method($value);
                    }

                    $this->blockTypes[] = $blockType;
                }
            }
        }
    }
}