<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use Webaccess\WCMSCore\Entities\MediaFolder;
use Webaccess\WCMSCore\Repositories\MediaFolderRepositoryInterface;

class JSONMediaFolderRepository implements MediaFolderRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'media_folders.json';
        $this->counter = 0;
        $this->mediaFolders = [];

        $this->loadFromJSON();
    }

    public function findByID($mediaFolderID)
    {
        foreach ($this->mediaFolders as $mediaFolder) {
            if ($mediaFolderID == $mediaFolder->getID()) {
                return $mediaFolder;
            }
        }

        return false;
    }

    public function findByPath($mediaFolderPath)
    {
        foreach ($this->mediaFolders as $mediaFolder) {
            if ($mediaFolderPath == $mediaFolder->getPath()) {
                return $mediaFolder;
            }
        }

        return false;
    }

    public function findAllByMediaFolder($mediaFolderID)
    {
        $mediaFolders = [];
        foreach ($this->mediaFolders as $mediaFolder) {
            if ($mediaFolderID == $mediaFolder->getParentID()) {
                $mediaFolders[]= $mediaFolder;
            }
        }

        return $mediaFolders;
    }

    public function findAll()
    {
        return $this->mediaFolders;
    }

    public function createMediaFolder(MediaFolder $mediaFolder)
    {
        $this->counter++;
        $mediaFolder->setID($this->counter);
        $this->mediaFolders[]= $mediaFolder;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateMediaFolder(MediaFolder $mediaFolder)
    {
        foreach ($this->mediaFolders as $i => $mediaFolderJSON) {
            if ($mediaFolder->getID() == $mediaFolderJSON->getID()) {
                $this->mediaFolders[$i] = $mediaFolder;
            }
        }

        $this->writeToJSON();
    }

    public function deleteMediaFolder($mediaFolderID)
    {
        foreach ($this->mediaFolders as $i => $mediaFolderJSON) {
            if ($mediaFolderJSON->getID() == $mediaFolderID) {
                unset($this->mediaFolders[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $mediaFolders = [];
        foreach ($this->mediaFolders as $mediaFolder) {
            $mediaFolders[]= [
                'id' => $mediaFolder->getID(),
                'name' => $mediaFolder->getName(),
                'parentID' => $mediaFolder->getParentID(),
                'path' => $mediaFolder->getPath(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $mediaFolders]));
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
            $mediaFolders = $data[1];

            if (is_array($mediaFolders) && sizeof($mediaFolders) > 0) {
                foreach ($mediaFolders as $mediaFolderData) {
                    $mediaFolder = new MediaFolder();
                    foreach ($mediaFolderData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $mediaFolder->$method($value);
                    }

                    $this->mediaFolders[] = $mediaFolder;
                }
            }
        }
    }
}