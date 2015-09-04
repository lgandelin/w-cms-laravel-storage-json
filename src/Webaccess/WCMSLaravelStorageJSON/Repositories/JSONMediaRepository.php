<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use Webaccess\WCMSCore\Entities\Media;
use Webaccess\WCMSCore\Repositories\MediaRepositoryInterface;

class JSONMediaRepository implements MediaRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'medias.json';
        $this->counter = 0;
        $this->medias = [];

        $this->loadFromJSON();
    }

    public function findByID($mediaID)
    {
        foreach ($this->medias as $media) {
            if ($mediaID == $media->getID()) {
                return $media;
            }
        }

        return false;
    }

    public function findAll()
    {
        return $this->medias;
    }

    public function createMedia(Media $media)
    {
        $this->counter++;
        $media->setID($this->counter);
        $this->medias[]= $media;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateMedia(Media $media)
    {
        foreach ($this->medias as $i => $mediaJSON) {
            if ($media->getID() == $mediaJSON->getID()) {
                $this->medias[$i] = $media;
            }
        }

        $this->writeToJSON();
    }

    public function deleteMedia($mediaID)
    {
        foreach ($this->medias as $i => $mediaJSON) {
            if ($mediaJSON->getID() == $mediaID) {
                unset($this->medias[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $medias = [];
        foreach ($this->medias as $media) {
            $medias[]= [
                'id' => $media->getID(),
                'name' => $media->getName(),
                'file_name' => $media->getFileName(),
                'alt' => $media->getAlt(),
                'title' => $media->getTitle(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $medias]));
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
            $medias = $data[1];

            if (is_array($medias) && sizeof($medias) > 0) {
                foreach ($medias as $mediaData) {
                    $media = new Media();
                    foreach ($mediaData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $media->$method($value);
                    }

                    $this->medias[] = $media;
                }
            }
        }
    }
}