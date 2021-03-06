<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use Webaccess\WCMSCore\Entities\MediaFormat;
use Webaccess\WCMSCore\Repositories\MediaFormatRepositoryInterface;

class JSONMediaFormatRepository implements MediaFormatRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'media_formats.json';
        $this->counter = 0;
        $this->mediaFormats = [];

        $this->loadFromJSON();
    }

    public function findByID($mediaFormatID)
    {
        foreach ($this->mediaFormats as $mediaFormat) {
            if ($mediaFormatID == $mediaFormat->getID()) {
                return $mediaFormat;
            }
        }

        return false;
    }

    public function findAll()
    {
        return $this->mediaFormats;
    }

    public function createMediaFormat(MediaFormat $mediaFormat)
    {
        $this->counter++;
        $mediaFormat->setID($this->counter);
        $this->mediaFormats[]= $mediaFormat;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateMediaFormat(MediaFormat $mediaFormat)
    {
        foreach ($this->mediaFormats as $i => $mediaFormatJSON) {
            if ($mediaFormat->getID() == $mediaFormatJSON->getID()) {
                $this->mediaFormats[$i] = $mediaFormat;
            }
        }

        $this->writeToJSON();
    }

    public function deleteMediaFormat($mediaFormatID)
    {
        foreach ($this->mediaFormats as $i => $mediaFormatJSON) {
            if ($mediaFormatJSON->getID() == $mediaFormatID) {
                unset($this->mediaFormats[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $mediaFormats = [];
        foreach ($this->mediaFormats as $mediaFormat) {
            $mediaFormats[]= [
                'id' => $mediaFormat->getID(),
                'name' => $mediaFormat->getName(),
                'width' => $mediaFormat->getWidth(),
                'height' => $mediaFormat->getHeight(),
                'preserve_ratio' => $mediaFormat->getPreserveRatio(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $mediaFormats]));
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
            $mediaFormats = $data[1];

            if (is_array($mediaFormats) && sizeof($mediaFormats) > 0) {
                foreach ($mediaFormats as $mediaFormatData) {
                    $mediaFormat = new MediaFormat();
                    foreach ($mediaFormatData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $mediaFormat->$method($value);
                    }

                    $this->mediaFormats[] = $mediaFormat;
                }
            }
        }
    }
}