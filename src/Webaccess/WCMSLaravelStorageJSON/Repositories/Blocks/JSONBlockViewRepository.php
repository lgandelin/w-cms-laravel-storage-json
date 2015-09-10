<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\ViewBlock;

class JSONBlockViewRepository
{
    public function getBlock($blockData) {
        return new ViewBlock();
    }
}
