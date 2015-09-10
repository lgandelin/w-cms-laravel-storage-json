<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks;

use Webaccess\WCMSCore\Entities\Blocks\HTMLBlock;

class JSONBlockHTMLRepository
{
    public function getBlock($blockData) {
        return new HTMLBlock();
    }
}
