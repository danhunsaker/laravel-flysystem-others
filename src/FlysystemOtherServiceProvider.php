<?php

namespace Danhunsaker\Laravel\Flysystem;

use App;
use Danhunsaker\Laravel\Flysystem\FlysystemServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
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
        parent::boot();

        if (class_exists('\League\Flysystem\EventableFilesystem\EventableFilesystem')) {
            $fsClass = \League\Flysystem\EventableFilesystem\EventableFilesystem::class;
        } else {
            $fsClass = \League\Flysystem\Filesystem::class;
        }

        if (class_exists('\Zhuxiaoqiao\Flysystem\BaiduBos\BaiduBosAdapter')) {
            Storage::extend('bos', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Zhuxiaoqiao\Flysystem\BaiduBos\BaiduBosAdapter(new \BaiduBce\Services\Bos\BosClient(Arr::except($config, ['driver', 'bucket'])), $config['bucket']));
            });
        }

        if (class_exists('\Enl\Flysystem\Cloudinary\CloudinaryAdapter')) {
            Storage::extend('cloudinary', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Enl\Flysystem\Cloudinary\CloudinaryAdapter(new \Enl\Flysystem\Cloudinary\ApiFacade($config)));
            });
        } elseif (class_exists('\T3chnik\FlysystemCloudinaryAdapter\CloudinaryAdapter')) {
            Storage::extend('cloudinary', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \T3chnik\FlysystemCloudinaryAdapter\CloudinaryAdapter($config, new \Cloudinary\Api));
            });
        }

        if (class_exists('\Rokde\Flysystem\Adapter\LocalDatabaseAdapter')) {
            Storage::extend('eloquent', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Rokde\Flysystem\Adapter\LocalDatabaseAdapter($app->make(Arr::get($config, 'model', '\Rokde\Flysystem\Adapter\Model\FileModel'))));
            });
        }

        if (class_exists('\Litipk\Flysystem\Fallback\FallbackAdapter')) {
            Storage::extend('fallback', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Litipk\Flysystem\Fallback\FallbackAdapter(Storage::disk($config['main'])->getAdapter(), Storage::disk($config['fallback'])->getAdapter()));
            });
        }

        if (class_exists('\Potherca\Flysystem\Github\GithubAdapter')) {
            Storage::extend('github', function ($app, $config) use ($fsClass) {
                $settings = new \Potherca\Flysystem\Github\Settings($config['project'], [\Potherca\Flysystem\Github\Settings::AUTHENTICATE_USING_TOKEN, $config['token']]);

                return new $fsClass(new \Potherca\Flysystem\Github\GithubAdapter(new \Potherca\Flysystem\Github\Api(new \Github\Client(), $settings)));
            });
        }

        if (class_exists('\Ignited\Flysystem\GoogleDrive\GoogleDriveAdapter')) {
            Storage::extend('gdrive', function ($app, $config) use ($fsClass) {
                $client = new \Google_Client();
                $client->setClientId($config['client_id']);
                $client->setClientSecret($config['secret']);
                $client->setAccessToken(json_encode([
                    "access_token" => $config['token'],
                    "expires_in"   => 3920,
                    "token_type"   => "Bearer",
                    "created"      => time()
                ]));

                return new $fsClass(new \Ignited\Flysystem\GoogleDrive\GoogleDriveAdapter(new \Google_Service_Drive($client)));
            });
        }

        if (class_exists('\Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter')) {
            Storage::extend('google', function ($app, $config) use ($fsClass) {
                $client = new \Google_Client();
                $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
                    $config['account'],
                    [\Google_Service_Storage::DEVSTORAGE_FULL_CONTROL],
                    file_get_contents($config['p12_file']),
                    $config['secret']
                ));
                $client->setDeveloperKey($config['developer_key']);

                return new $fsClass(new \Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter(new \Google_Service_Storage($client), $config['bucket']));
            });
        }

        if (class_exists('\Litipk\Flysystem\Fallback\FallbackAdapter') && class_exists('\League\Flysystem\Replicate\ReplicateAdapter')) {
            Storage::extend('mirror', function ($app, $config) use ($fsClass) {
                return new $fsClass($this->buildMirrors($config['disks']));
            });
        }

        if (class_exists('\JacekBarecki\FlysystemOneDrive\Adapter\OneDriveAdapter')) {
            Storage::extend('onedrive', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \JacekBarecki\FlysystemOneDrive\Adapter\OneDriveAdapter(new \JacekBarecki\FlysystemOneDrive\Client\OneDriveClient(Arr::get($config, 'access_token'), new \GuzzleHttp\Client())));
            });
        } elseif (class_exists('\Ignited\Flysystem\OneDrive\OneDriveAdapter')) {
            Storage::extend('onedrive', function ($app, $config) use ($fsClass) {
                $oneConfig = Arr::only($config, ['base_url', 'access_token']);
                if ($config['use_logger']) {
                    $logger = Log::getMonolog();
                } else {
                    $logger = null;
                }

                return new $fsClass(new \Ignited\Flysystem\OneDrive\OneDriveAdapter(\Ignited\Flysystem\OneDrive\OneDriveClient::factory($oneConfig, $logger)));
            });
        }

        if (class_exists('\Orzcc\AliyunOss\AliyunOssAdapter')) {
            Storage::extend('oss', function ($app, $config) use ($fsClass) {
                $ossconfig = [
                    'AccessKeyId'       => $config['access_id'],
                    'AccessKeySecret'   => $config['access_key']
                ];

                if (isset($config['endpoint']) && ! empty($config['endpoint'])) {
                    $ossconfig['Endpoint'] = $config['endpoint'];
                }

                return new $fsClass(new \Orzcc\AliyunOss\AliyunOssAdapter(\Aliyun\OSS\OSSClient::factory($ossconfig), $config['bucket'], $config['prefix']));
            });
        } elseif (class_exists('\Shion\Aliyun\OSS\Adapter\OSSAdapter')) {
            Storage::extend('oss', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Shion\Aliyun\OSS\Adapter\OSSAdapter(new \Shion\Aliyun\OSS\Client\OSSClient(Arr::except($config, ['driver', 'bucket'])), $config['bucket']));
            });
        }

        if (class_exists('\EQingdan\Flysystem\Qiniu\QiniuAdapter')) {
            Storage::extend('qiniu', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \EQingdan\Flysystem\Qiniu\QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket'], $config['domain']));
            });
        } elseif (class_exists('\Polev\Flysystem\Qiniu\QiniuAdapter')) {
            Storage::extend('qiniu', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Polev\Flysystem\Qiniu\QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket']));
            });
        }

        if (class_exists('\Danhunsaker\Flysystem\Redis\RedisAdapter')) {
            Storage::extend('redis', function ($app, $config) use ($fsClass) {
                $client = $app->make('redis')->connection(Arr::get($config, 'connection', 'default'));

                return new $fsClass(new \Danhunsaker\Flysystem\Redis\RedisAdapter($client));
            });
        }

        if (class_exists('\Engineor\Flysystem\RunaboveAdapter')) {
            Storage::extend('runabove', function ($app, $config) use ($fsClass) {
                $config['region'] = constant(\Engineor\Flysystem\Runabove::class . '::REGION_' . strtoupper($config['region']));

                $client = new \Engineor\Flysystem\Runabove(Arr::except($config, ['driver']));

                return new $fsClass(new \Engineor\Flysystem\RunaboveAdapter($client->getContainer()));
            });
        }

        if (class_exists('\Coldwind\Filesystem\KvdbAdapter')) {
            Storage::extend('sae', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Coldwind\Filesystem\KvdbAdapter(new \Coldwind\Filesystem\KvdbClient));
            });
        }

        if (class_exists('\RobGridley\Flysystem\Smb\SmbAdapter')) {
            Storage::extend('smb', function ($app, $config) use ($fsClass) {
                $server = new \Icewind\SMB\Server($config['host'], $config['username'], $config['password']);
                $share = $server->getShare($config['path']);

                return new $fsClass(new \RobGridley\Flysystem\Smb\SmbAdapter($share));
            });
        }

        if (class_exists('\Emgag\Flysystem\TempdirAdapter')) {
            Storage::extend('temp', function ($app, $config) use ($fsClass) {
                return new $fsClass(new \Emgag\Flysystem\TempdirAdapter(Arr::get($config, 'prefix'), Arr::get($config, 'tempdir')));
            });
        }
    }

    protected function buildMirrors($disks)
    {
        $main = Storage::disk(reset($disks))->getAdapter();

        if (count($disks) > 2) {
            $second = $this->buildMirrors(array_slice($disks, 1));
        } else {
            $second = Storage::disk(end($disks))->getAdapter();
        }

        return new \League\Flysystem\Replicate\ReplicateAdapter(new \Litipk\Flysystem\Fallback\FallbackAdapter($main, $second, true), $second);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $source = realpath(__DIR__ . '/filesystems.php');

        $this->publishes([$source => config_path('filesystems.php')]);

        $this->mergeConfigFrom($source, 'filesystems');

        if (class_exists('Twistor\FlysystemStreamWrapper')) {
            App::register('Danhunsaker\Laravel\Flysystem\FlysystemStreamWrapperServiceProvider');
        }
    }
}
