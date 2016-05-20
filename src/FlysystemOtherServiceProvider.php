<?php

namespace Danhunsaker\Laravel\Flysystem;

use App;
use Danhunsaker\Laravel\Flysystem\FlysystemOtherManager;
use Danhunsaker\Laravel\Flysystem\FlysystemServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\Arr;
use Log;
use Storage;

class FlysystemOtherServiceProvider extends FlysystemServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
