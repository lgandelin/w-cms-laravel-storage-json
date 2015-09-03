<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\ArticleBlock;

class JSONBlockArticleRepository
{
    public function getBlock($blockData) {
        $block = new ArticleBlock();
        if (isset($blockData['article_id'])) {
            $block->setArticleID($blockData['article_id']);
        }

        return $block;
    }
} 