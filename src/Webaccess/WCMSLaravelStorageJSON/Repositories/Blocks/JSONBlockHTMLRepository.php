<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use CMS\Entities\Blocks\HTMLBlock;

class JSONBlockHTMLRepository
{
    public function getBlock($blockData) {
        $block = new HTMLBlock();
        if (isset($blockData['html'])) {
            $block->setHTML($blockData['html']);
        }

        return $block;
    }
}
