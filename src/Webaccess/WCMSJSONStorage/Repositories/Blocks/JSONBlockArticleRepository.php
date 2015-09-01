<?php

namespace Webaccess\WCMSJSONStorage\Repositories\Blocks;

use CMS\Entities\Blocks\ArticleBlock;

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