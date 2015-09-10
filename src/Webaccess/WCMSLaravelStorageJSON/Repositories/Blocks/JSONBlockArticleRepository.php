<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\ArticleBlock;

class JSONBlockArticleRepository
{
    public function getBlock($blockData) {
        return new ArticleBlock();
    }
} 