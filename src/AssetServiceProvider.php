<?php

namespace Newnet\Asset;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResolver();

        $this->registerDispatcher();

        $this->registerAsset();

        $this->registerHtmlBuilder();
    }

    public function boot()
    {
        Blade::directive('assetadd', function ($expression) {
            return "<?php \Newnet\Asset\Helpers\BladeHelper::renderBlade($expression); ?>";
        });
    }

    /**
     * Register the service provider.
     */
    protected function registerAsset(): void
    {
        $this->app->singleton('newnet.asset', static function (Container $app) {
            return new Factory($app->make('newnet.asset.dispatcher'));
        });
    }

    /**
     * Register the service provider.
     */
    protected function registerDispatcher(): void
    {
        $this->app->singleton('newnet.asset.dispatcher', static function ($app) {
            return new Dispatcher(
                $app->make('files'),
                $app->make('newnet.html'),
                $app->make('newnet.asset.resolver'),
                $app->publicPath()
            );
        });
    }

    /**
     * Register the service provider.
     */
    protected function registerResolver(): void
    {
        $this->app->singleton('newnet.asset.resolver', DependencyResolver::class);
    }

    /**
     * Register the service provider.
     */
    protected function registerHtmlBuilder(): void
    {
        $this->app->singleton('newnet.html', HtmlBuilder::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'newnet.html',
            'newnet.asset',
            'newnet.asset.dispatcher',
            'newnet.asset.resolver',
        ];
    }
}
