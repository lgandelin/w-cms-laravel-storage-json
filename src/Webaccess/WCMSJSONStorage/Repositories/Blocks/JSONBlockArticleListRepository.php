<?php

namespace Webaccess\WCMSJSONStorage\Repositories\Blocks;

use CMS\Entities\Blocks\ArticleListBlock;

class JSONBlockArticleListRepository
{
    public function getBlock($blockData) {
        $block = new ArticleListBlock();
        if (isset($blockData['article_id'])) {
            $block->setArticleListCategoryID($blockData['article_list_category_id']);
            $block->setArticleListOrder($blockData['article_list_order']);
            $block->setArticleListNumber($blockData['article_list_number']);
        }

        return $block;
    }
} 