<?php

namespace Danhunsaker\Laravel\Flysystem;

use Danhunsaker\Laravel\Flysystem\FlysystemOtherManager;
use Danhunsaker\Laravel\Flysystem\FlysystemServiceProvider;

class FlysystemOtherServiceProvider extends FlysystemServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ((array) $this->app['config']['filesystems.autowrap'] as $drive) {
            $this->app['filesystem']->disk($drive);
        }
    }

    /**
     * Register the filesystem manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('filesystem', function () {
            return new FlysystemOtherManager($this->app);
        });
    }
}
