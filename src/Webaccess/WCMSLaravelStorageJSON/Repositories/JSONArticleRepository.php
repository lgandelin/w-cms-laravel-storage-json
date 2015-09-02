<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use CMS\Entities\Article;
use CMS\Repositories\ArticleRepositoryInterface;

class JSONArticleRepository implements ArticleRepositoryInterface
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/articles.json';
        $this->counter = 0;
        $this->articles = [];

        $this->loadFromJSON();
    }

    public function findByID($articleID)
    {
        foreach ($this->articles as $article) {
            if ($articleID == $article->getID()) {
                return $article;
            }
        }

        return false;
    }

    public function findByPageID($pageID)
    {
        foreach ($this->articles as $article) {
            if ($pageID == $article->getPageID()) {
                return $article;
            }
        }

        return false;
    }

    public function findByTitle($articleTitle)
    {
        foreach ($this->articles as $article) {
            if ($articleTitle == $article->getTitle()) {
                return $article;
            }
        }

        return false;    }

    public function findAll($langID = null)
    {
        if ($langID) {
            $articles = [];
            foreach ($this->articles as $i => $article) {
                if ($article->getLangID() == $langID) {
                    $articles[]= $article;
                }
            }

            return $articles;
        }

        return $this->articles;
    }

    public function createArticle(Article $article)
    {
        $this->counter++;
        $article->setID($this->counter);
        $this->articles[]= $article;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateArticle(Article $article)
    {
        foreach ($this->articles as $i => $articleJSON) {
            if ($article->getID() == $articleJSON->getID()) {
                $this->articles[$i] = $article;
            }
        }

        $this->writeToJSON();
    }

    public function deleteArticle($articleID)
    {
        foreach ($this->articles as $i => $articleJSON) {
            if ($articleJSON->getID() == $articleID) {
                unset($this->articles[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $articles = [];
        foreach ($this->articles as $article) {
            $articles[]= [
                'id' => $article->getID(),
                'title' => $article->getTitle(),
                'summary' => $article->getSummary(),
                'text' => $article->getText(),
                'lang_id' => $article->getLangID(),
                'category_id' => $article->getCategoryID(),
                'author_id' => $article->getAuthorID(),
                'page_id' => $article->getPageID(),
                'media_id' => $article->getMediaID(),
                'publication_date' => $article->getPublicationDate(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $articles]));
    }

    private function loadFromJSON()
    {
        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $articles = $data[1];

            if (is_array($articles) && sizeof($articles) > 0) {
                foreach ($articles as $articleData) {
                    $article = new Article();
                    foreach ($articleData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $article->$method($value);
                    }

                    $this->articles[] = $article;
                }
            }
        }
    }
}