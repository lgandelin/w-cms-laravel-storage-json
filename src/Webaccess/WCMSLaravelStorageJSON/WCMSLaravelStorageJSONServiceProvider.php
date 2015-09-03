<?php

namespace Webaccess\WCMSLaravelStorageJSON;

use Illuminate\Support\ServiceProvider;
use Webaccess\WCMSCore\Context;
use Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks\JSONBlockArticleListRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks\JSONBlockArticleRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks\JSONBlockHTMLRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks\JSONBlockMediaRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks\JSONBlockMenuRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\Blocks\JSONBlockViewRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONAreaRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONArticleCategoryRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONArticleRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONBlockRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONBlockTypeRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONLangRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONMediaFormatRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONMediaRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONMenuItemRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONMenuRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONPageRepository;
use Webaccess\WCMSLaravelStorageJSON\Repositories\JSONUserRepository;

class WCMSLaravelStorageJSONServiceProvider extends ServiceProvider {

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
        $jsonFolder = storage_path() . '/w-cms/';

        Context::add('block_html', new JSONBlockHTMLRepository());
        Context::add('block_menu', new JSONBlockMenuRepository());
        Context::add('block_article', new JSONBlockArticleRepository());
        Context::add('block_article_list', new JSONBlockArticleListRepository());
        Context::add('block_media', new JSONBlockMediaRepository());
        Context::add('block_view', new JSONBlockViewRepository());

        Context::add('page', new JSONPageRepository($jsonFolder));
        Context::add('area', new JSONAreaRepository($jsonFolder));
        Context::add('block', new JSONBlockRepository($jsonFolder));
        Context::add('lang', new JSONLangRepository($jsonFolder));
        Context::add('menu', new JSONMenuRepository($jsonFolder));
        Context::add('menu_item', new JSONMenuItemRepository($jsonFolder));
        Context::add('media', new JSONMediaRepository($jsonFolder));
        Context::add('media_format', new JSONMediaFormatRepository($jsonFolder));
        Context::add('article', new JSONArticleRepository($jsonFolder));
        Context::add('user', new JSONUserRepository($jsonFolder));
        Context::add('article_category', new JSONArticleCategoryRepository($jsonFolder));
        Context::add('block_type', new JSONBlockTypeRepository($jsonFolder));
    }
}
