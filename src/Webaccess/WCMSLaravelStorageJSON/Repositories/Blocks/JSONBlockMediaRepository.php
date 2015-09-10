<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\MediaBlock;

class JSONBlockMediaRepository
{
    public function getBlock($blockData) {
        return new MediaBlock();
    }
} 