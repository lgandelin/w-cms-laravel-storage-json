<?php

namespace Webaccess\WCMSJSONStorage\Repositories\Blocks;

use CMS\Entities\Blocks\ViewBlock;

class JSONBlockViewRepository
{
    public function getBlock($blockData) {
        $block = new ViewBlock();
        if (isset($blockData['article_id'])) {
            $block->setViewPath($blockData['view_path']);
        }

        return $block;
    }
}
