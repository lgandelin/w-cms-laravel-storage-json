<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use Webaccess\WCMSCore\Entities\Version;
use Webaccess\WCMSCore\Repositories\VersionRepositoryInterface;

class JSONVersionRepository implements VersionRepositoryInterface
{
    public function __construct($jsonFolder)
    {
        $this->jsonFolder = $jsonFolder;
        $this->json = $this->jsonFolder . 'versions.json';
        $this->counter = 0;
        $this->versions = [];

        $this->loadFromJSON();
    }

    public function findByID($versionID)
    {
        foreach ($this->versions as $version) {
            if ($versionID == $version->getID()) {
                return $version;
            }
        }

        return false;
    }

    public function findByPageID($pageID)
    {
        $versions = [];
        foreach ($this->versions as $version) {
            if ($version->getPageID() == $pageID) {
                $versions[]= $version;
            }
        }

        return $versions;
    }

    public function findAll()
    {
        return $this->versions;
    }

    public function createVersion(Version $version)
    {
        $this->counter++;
        $version->setID($this->counter);
        $this->versions[]= $version;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateVersion(Version $version)
    {
        foreach ($this->versions as $i => $versionJSON) {
            if ($version->getID() == $versionJSON->getID()) {
                $this->versions[$i] = $version;
            }
        }

        $this->writeToJSON();
    }

    public function deleteVersion($versionID)
    {
        foreach ($this->versions as $i => $versionJSON) {
            if ($versionJSON->getID() == $versionID) {
                unset($this->versions[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $versions = [];
        foreach ($this->versions as $version) {
            $versions[]= [
                'id' => $version->getID(),
                'page_id' => $version->getPageID(),
                'number' => $version->getNumber(),
                'updated_date' => $version->getUpdatedDate(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $versions]));
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
            $versions = $data[1];

            if (is_array($versions) && sizeof($versions) > 0) {
                foreach ($versions as $versionData) {
                    $version = new Version();
                    foreach ($versionData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $version->$method($value);
                    }

                    $this->versions[] = $version;
                }
            }
        }
    }
}