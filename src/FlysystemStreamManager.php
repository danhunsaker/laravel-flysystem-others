<?php

namespace Danhunsaker\Laravel\Flysystem;

use Illuminate\Filesystem\FilesystemManager;
use Twistor\FlysystemStreamWrapper;

class FlysystemStreamManager extends FilesystemManager
{
    /**
     * Resolve the given disk.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (isset($this->customCreators[$config['driver']])) {
            $adapter = $this->callCustomCreator($config);
        } else {
            $adapter = $this->{'create' . ucfirst($config['driver']) . 'Driver'}($config);
        }

        FlysystemStreamWrapper::register($name, $adapter->getDriver());

        return $adapter;
    }
}
