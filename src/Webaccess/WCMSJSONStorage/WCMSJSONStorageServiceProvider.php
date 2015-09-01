<?php

namespace Webaccess\WCMSJSONStorage;

use CMS\Context;
use Illuminate\Support\ServiceProvider;
use Webaccess\WCMSJSONStorage\Repositories\Blocks\JSONBlockArticleListRepository;
use Webaccess\WCMSJSONStorage\Repositories\Blocks\JSONBlockArticleRepository;
use Webaccess\WCMSJSONStorage\Repositories\Blocks\JSONBlockHTMLRepository;
use Webaccess\WCMSJSONStorage\Repositories\Blocks\JSONBlockMediaRepository;
use Webaccess\WCMSJSONStorage\Repositories\Blocks\JSONBlockMenuRepository;
use Webaccess\WCMSJSONStorage\Repositories\Blocks\JSONBlockViewRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONAreaRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONArticleCategoryRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONArticleRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONBlockRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONBlockTypeRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONLangRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONMediaFormatRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONMediaRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONMenuItemRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONMenuRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONPageRepository;
use Webaccess\WCMSJSONStorage\Repositories\JSONUserRepository;

class WCMSJSONStorageServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Init repositories
        Context::add('block_html', new JSONBlockHTMLRepository());
        Context::add('block_menu', new JSONBlockMenuRepository());
        Context::add('block_article', new JSONBlockArticleRepository());
        Context::add('block_article_list', new JSONBlockArticleListRepository());
        Context::add('block_media', new JSONBlockMediaRepository());
        Context::add('block_view', new JSONBlockViewRepository());

        Context::add('page', new JSONPageRepository());
        Context::add('area', new JSONAreaRepository());
        Context::add('block', new JSONBlockRepository());
        Context::add('lang', new JSONLangRepository());
        Context::add('menu', new JSONMenuRepository());
        Context::add('menu_item', new JSONMenuItemRepository());
        Context::add('media', new JSONMediaRepository());
        Context::add('media_format', new JSONMediaFormatRepository());
        Context::add('article', new JSONArticleRepository());
        Context::add('user', new JSONUserRepository());
        Context::add('article_category', new JSONArticleCategoryRepository());
        Context::add('block_type', new JSONBlockTypeRepository());
    }
}
