<?php

namespace Webaccess\WCMSJSONStorage\Repositories;

use CMS\Entities\ArticleCategory;
use CMS\Repositories\ArticleCategoryRepositoryInterface;

class JSONArticleCategoryRepository implements ArticleCategoryRepositoryInterface
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/article_categories.json';
        $this->counter = 1;
        $this->articleCategories = [];

        $this->loadFromJSON();
    }

    public function findByID($articleCategoryID)
    {
        foreach ($this->articleCategories as $articleCategory) {
            if ($articleCategoryID == $articleCategory->getID()) {
                return $articleCategory;
            }
        }

        return false;
    }

    public function findAll($langID = null)
    {
        if ($langID) {
            $articleCategories = [];
            foreach ($this->articleCategories as $i => $articleCategory) {
                if ($articleCategory->getLangID() == $langID) {
                    $articleCategories[]= $articleCategory;
                }
            }

            return $articleCategories;
        }

        return $this->articleCategories;
    }

    public function createArticleCategory(ArticleCategory $articleCategory)
    {
        $this->counter++;
        $articleCategory->setID($this->counter);
        $this->articleCategories[]= $articleCategory;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateArticleCategory(ArticleCategory $articleCategory)
    {
        foreach ($this->articleCategories as $i => $articleCategoryJSON) {
            if ($articleCategory->getID() == $articleCategoryJSON->getID()) {
                $this->articleCategories[$i] = $articleCategory;
            }
        }

        $this->writeToJSON();
    }

    public function deleteArticleCategory($articleCategoryID)
    {
        foreach ($this->articleCategories as $i => $articleCategoryJSON) {
            if ($articleCategoryJSON->getID() == $articleCategoryID) {
                unset($this->articleCategories[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $articleCategories = [];
        foreach ($this->articleCategories as $articleCategory) {
            $articleCategories[]= [
                'id' => $articleCategory->getID(),
                'name' => $articleCategory->getName(),
                'description' => $articleCategory->getDescription(),
                'lang_id' => $articleCategory->getLangID(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $articleCategories]));
    }

    private function loadFromJSON()
    {
        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $articleCategories = $data[1];

            if (is_array($articleCategories) && sizeof($articleCategories) > 0) {
                foreach ($articleCategories as $articleCategoryData) {
                    $articleCategory = new ArticleCategory();
                    foreach ($articleCategoryData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $articleCategory->$method($value);
                    }

                    $this->articleCategories[] = $articleCategory;
                }
            }
        }
    }
}