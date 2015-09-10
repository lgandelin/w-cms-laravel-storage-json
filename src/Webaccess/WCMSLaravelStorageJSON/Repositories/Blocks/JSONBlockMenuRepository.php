<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\MenuBlock;

class JSONBlockMenuRepository
{
    public function getBlock($blockData) {
        return new MenuBlock();
    }
}
