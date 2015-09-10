<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\ArticleListBlock;

class JSONBlockArticleListRepository
{
    public function getBlock($blockData) {
        return new ArticleListBlock();
    }
} 