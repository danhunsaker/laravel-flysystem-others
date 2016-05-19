<?php

namespace Danhunsaker\Laravel\Flysystem;

use Danhunsaker\Laravel\Flysystem\FlysystemStreamManager;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Storage;

class FlysystemStreamWrapperServiceProvider extends FilesystemServiceProvider
{
    /**
     * Register the filesystem manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        if (class_exists('Twistor\FlysystemStreamWrapper')) {
            $this->app->singleton('filesystem', function () {
                return new FlysystemStreamManager($this->app);
            });

            foreach (config('filesystems.autowrap') as $disk) {
                Storage::disk($disk);
            }
        } else {
            return parent::registerManager();
        }
    }
}
