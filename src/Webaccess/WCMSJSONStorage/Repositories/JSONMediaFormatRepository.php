<?php

namespace Webaccess\WCMSJSONStorage\Repositories;

use CMS\Entities\MediaFormat;
use CMS\Repositories\MediaFormatRepositoryInterface;

class JSONMediaFormatRepository implements MediaFormatRepositoryInterface
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/media_formats.json';
        $this->counter = 1;
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
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $mediaFormats]));
    }

    private function loadFromJSON()
    {
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