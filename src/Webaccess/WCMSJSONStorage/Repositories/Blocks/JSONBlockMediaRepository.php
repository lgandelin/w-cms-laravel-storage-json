<?php

namespace Webaccess\WCMSJSONStorage\Repositories\Blocks;

use CMS\Entities\Blocks\MediaBlock;

class JSONBlockMediaRepository
{
    public function getBlock($blockData) {
        $block = new MediaBlock();
        if (isset($blockData['article_id'])) {
            $block->setMediaID($blockData['media_id']);
            $block->setMediaLink($blockData['media_link']);
            $block->setMediaFormatID($blockData['media_format_id']);
        }

        return $block;
    }
} 