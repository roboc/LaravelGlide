<?php

namespace Roboc\Glide;

use Illuminate\Support\ServiceProvider;

/**
 * Class GlideImageServiceProvider
 * @package Roboc\Glide
 */
class GlideImageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes( [
            __DIR__ . '/../config/laravel-glide.php' => config_path( '/laravel-glide.php' ),
        ], 'config' );
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . '/../config/laravel-glide.php', 'laravel-glide' );

        $configuration = config( 'laravel-glide' );

        $this->app->bind( 'glide-image', function () use ( $configuration )
        {
            return new GlideImageService( $configuration );
        } );
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [ 'glide-image' ];
    }
}
