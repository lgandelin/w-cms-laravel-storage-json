<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\MenuBlock;

class JSONBlockMenuRepository
{
    public function getBlock($blockData) {
        $block = new MenuBlock();
        if (isset($blockData['menu_id'])) {
            $block->setMenuID($blockData['menu_id']);
        }

        return $block;
    }
}
