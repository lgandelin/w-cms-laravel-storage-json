<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use CMS\Entities\Page;
use CMS\Repositories\PageRepositoryInterface;

class JSONPageRepository implements PageRepositoryInterface
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/pages.json';
        $this->counter = 1;
        $this->pages = [];

        $this->loadFromJSON();
    }

    public function findByID($pageID)
    {
        foreach ($this->pages as $page) {
            if ($pageID == $page->getID()) {
                return $page;
            }
        }

        return false;
    }

    public function findByUri($pageUri)
    {
        foreach ($this->pages as $page) {
            if ($pageUri == $page->getURI()) {
                return $page;
            }
        }

        return false;
    }

    public function findByUriAndLangID($pageUri, $langID)
    {
        foreach ($this->pages as $page) {
            if ($pageUri == $page->getURI() && $langID == $page->getLangID()) {
                return $page;
            }
        }

        return false;
    }

    public function findByIdentifier($pageIdentifier)
    {
        foreach ($this->pages as $page) {
            if ($pageIdentifier == $page->getIdentifier()) {
                return $page;
            }
        }

        return false;
    }

    public function findAll($pageID = null)
    {
        return $this->pages;
    }

    public function findMasterPages()
    {
        return [];
    }


    public function createPage(Page $page)
    {
        $this->counter++;
        $page->setID($this->counter);
        $this->pages[]= $page;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updatePage(Page $page)
    {
        foreach ($this->pages as $i => $pageJSON) {
            if ($page->getID() == $pageJSON->getID()) {
                $this->pages[$i] = $page;
            }
        }

        $this->writeToJSON();
    }

    public function deletePage($pageID)
    {
        foreach ($this->pages as $i => $pageJSON) {
            if ($pageJSON->getID() == $pageID) {
                unset($this->pages[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $pages = [];
        foreach ($this->pages as $page) {
            $pages[]= [
                'id' => $page->getID(),
                'name' => $page->getName(),
                'identifier' => $page->getIdentifier(),
                'uri' => $page->getUri(),
                'lang_id' => $page->getLangID(),
                'meta_title' => $page->getMetaTitle(),
                'meta_description' => $page->getMetaDescription(),
                'meta_keywords' => $page->getMetaKeywords(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $pages]));
    }

    private function loadFromJSON()
    {
        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $pages = $data[1];

            if (is_array($pages) && sizeof($pages) > 0) {
                foreach ($pages as $pageData) {
                    $page = new Page();
                    $page->setID($pageData['id']);
                    $page->setName($pageData['name']);
                    $page->setIdentifier($pageData['identifier']);
                    $page->setUri($pageData['uri']);
                    $page->setLangID($pageData['lang_id']);
                    $page->setMetaTitle($pageData['meta_title']);
                    $page->setMetaDescription($pageData['meta_description']);
                    $page->setMetaKeywords($pageData['meta_keywords']);

                    $this->pages[] = $page;
                }
            }
        }
    }
}