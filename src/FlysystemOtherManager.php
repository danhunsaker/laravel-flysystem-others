<?php

namespace Danhunsaker\Laravel\Flysystem;

use Danhunsaker\Laravel\Flysystem\FlysystemManager;

class FlysystemOtherManager extends FlysystemManager
{
    /**
     * Create a new filesystem manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        if (class_exists('\Zhuxiaoqiao\Flysystem\BaiduBos\BaiduBosAdapter')) {
            $this->extend('bos', function ($app, $config) {
                return $this->createFlysystem(new \Zhuxiaoqiao\Flysystem\BaiduBos\BaiduBosAdapter(new \BaiduBce\Services\Bos\BosClient(Arr::except($config, ['driver', 'bucket'])), $config['bucket']), $config);
            });
        }

        if (class_exists('\Enl\Flysystem\Cloudinary\CloudinaryAdapter')) {
            $this->extend('cloudinary', function ($app, $config) {
                return $this->createFlysystem(new \Enl\Flysystem\Cloudinary\CloudinaryAdapter(new \Enl\Flysystem\Cloudinary\ApiFacade($config)), $config);
            });
        } elseif (class_exists('\T3chnik\FlysystemCloudinaryAdapter\CloudinaryAdapter')) {
            $this->extend('cloudinary', function ($app, $config) {
                return $this->createFlysystem(new \T3chnik\FlysystemCloudinaryAdapter\CloudinaryAdapter($config, new \Cloudinary\Api), $config);
            });
        }

        if (class_exists('\Rokde\Flysystem\Adapter\LocalDatabaseAdapter')) {
            $this->extend('eloquent', function ($app, $config) {
                return $this->createFlysystem(new \Rokde\Flysystem\Adapter\LocalDatabaseAdapter($app->make(Arr::get($config, 'model', '\Rokde\Flysystem\Adapter\Model\FileModel'))), $config);
            });
        }

        if (class_exists('\Litipk\Flysystem\Fallback\FallbackAdapter')) {
            $this->extend('fallback', function ($app, $config) {
                return $this->createFlysystem(new \Litipk\Flysystem\Fallback\FallbackAdapter($this->disk($config['main'])->getAdapter(), $this->disk($config['fallback'])->getAdapter()), $config);
            });
        }

        if (class_exists('\Potherca\Flysystem\Github\GithubAdapter')) {
            $this->extend('github', function ($app, $config) {
                $settings = new \Potherca\Flysystem\Github\Settings($config['project'], [\Potherca\Flysystem\Github\Settings::AUTHENTICATE_USING_TOKEN, $config['token']]);

                return $this->createFlysystem(new \Potherca\Flysystem\Github\GithubAdapter(new \Potherca\Flysystem\Github\Api(new \Github\Client(), $settings)), $config);
            });
        }

        if (class_exists('\Ignited\Flysystem\GoogleDrive\GoogleDriveAdapter')) {
            $this->extend('gdrive', function ($app, $config) {
                $client = new \Google_Client();
                $client->setClientId($config['client_id']);
                $client->setClientSecret($config['secret']);
                $client->setAccessToken(json_encode([
                    "access_token" => $config['token'],
                    "expires_in"   => 3920,
                    "token_type"   => "Bearer",
                    "created"      => time()
                ]));

                return $this->createFlysystem(new \Ignited\Flysystem\GoogleDrive\GoogleDriveAdapter(new \Google_Service_Drive($client)), $config);
            });
        }

        if (class_exists('\Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter')) {
            $this->extend('google', function ($app, $config) {
                $client = new \Google_Client();
                $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
                    $config['account'],
                    [\Google_Service_Storage::DEVSTORAGE_FULL_CONTROL],
                    file_get_contents($config['p12_file']),
                    $config['secret']
                ));
                $client->setDeveloperKey($config['developer_key']);

                return $this->createFlysystem(new \Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter(new \Google_Service_Storage($client), $config['bucket']), $config);
            });
        }

        if (class_exists('\Litipk\Flysystem\Fallback\FallbackAdapter') && class_exists('\League\Flysystem\Replicate\ReplicateAdapter')) {
            $this->extend('mirror', function ($app, $config) {
                return $this->createFlysystem($this->buildMirrors($config['disks']), $config);
            });
        }

        if (class_exists('\JacekBarecki\FlysystemOneDrive\Adapter\OneDriveAdapter')) {
            $this->extend('onedrive', function ($app, $config) {
                return $this->createFlysystem(new \JacekBarecki\FlysystemOneDrive\Adapter\OneDriveAdapter(new \JacekBarecki\FlysystemOneDrive\Client\OneDriveClient(Arr::get($config, 'access_token'), new \GuzzleHttp\Client())), $config);
            });
        } elseif (class_exists('\Ignited\Flysystem\OneDrive\OneDriveAdapter')) {
            $this->extend('onedrive', function ($app, $config) {
                $oneConfig = Arr::only($config, ['base_url', 'access_token']);
                if ($config['use_logger']) {
                    $logger = Log::getMonolog();
                } else {
                    $logger = null;
                }

                return $this->createFlysystem(new \Ignited\Flysystem\OneDrive\OneDriveAdapter(\Ignited\Flysystem\OneDrive\OneDriveClient::factory($oneConfig, $logger)), $config);
            });
        }

        if (class_exists('\Orzcc\AliyunOss\AliyunOssAdapter')) {
            $this->extend('oss', function ($app, $config) {
                $ossconfig = [
                    'AccessKeyId'       => $config['access_id'],
                    'AccessKeySecret'   => $config['access_key']
                ];

                if (isset($config['endpoint']) && ! empty($config['endpoint'])) {
                    $ossconfig['Endpoint'] = $config['endpoint'];
                }

                return $this->createFlysystem(new \Orzcc\AliyunOss\AliyunOssAdapter(\Aliyun\OSS\OSSClient::factory($ossconfig), $config['bucket'], $config['prefix']), $config);
            });
        } elseif (class_exists('\Shion\Aliyun\OSS\Adapter\OSSAdapter')) {
            $this->extend('oss', function ($app, $config) {
                return $this->createFlysystem(new \Shion\Aliyun\OSS\Adapter\OSSAdapter(new \Shion\Aliyun\OSS\Client\OSSClient(Arr::except($config, ['driver', 'bucket'])), $config['bucket']), $config);
            });
        }

        if (class_exists('\EQingdan\Flysystem\Qiniu\QiniuAdapter')) {
            $this->extend('qiniu', function ($app, $config) {
                return $this->createFlysystem(new \EQingdan\Flysystem\Qiniu\QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket'], $config['domain']), $config);
            });
        } elseif (class_exists('\Polev\Flysystem\Qiniu\QiniuAdapter')) {
            $this->extend('qiniu', function ($app, $config) {
                return $this->createFlysystem(new \Polev\Flysystem\Qiniu\QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket']), $config);
            });
        }

        if (class_exists('\Danhunsaker\Flysystem\Redis\RedisAdapter')) {
            $this->extend('redis', function ($app, $config) {
                $client = $app->make('redis')->connection(Arr::get($config, 'connection', 'default'));

                return $this->createFlysystem(new \Danhunsaker\Flysystem\Redis\RedisAdapter($client), $config);
            });
        }

        if (class_exists('\Engineor\Flysystem\RunaboveAdapter')) {
            $this->extend('runabove', function ($app, $config) {
                $config['region'] = constant(\Engineor\Flysystem\Runabove::class . '::REGION_' . strtoupper($config['region']));

                $client = new \Engineor\Flysystem\Runabove(Arr::except($config, ['driver']));

                return $this->createFlysystem(new \Engineor\Flysystem\RunaboveAdapter($client->getContainer()), $config);
            });
        }

        if (class_exists('\Coldwind\Filesystem\KvdbAdapter')) {
            $this->extend('sae', function ($app, $config) {
                return $this->createFlysystem(new \Coldwind\Filesystem\KvdbAdapter(new \Coldwind\Filesystem\KvdbClient), $config);
            });
        }

        if (class_exists('\RobGridley\Flysystem\Smb\SmbAdapter')) {
            $this->extend('smb', function ($app, $config) {
                $server = new \Icewind\SMB\Server($config['host'], $config['username'], $config['password']);
                $share = $server->getShare($config['path']);

                return $this->createFlysystem(new \RobGridley\Flysystem\Smb\SmbAdapter($share), $config);
            });
        }

        if (class_exists('\Emgag\Flysystem\TempdirAdapter')) {
            $this->extend('temp', function ($app, $config) {
                return $this->createFlysystem(new \Emgag\Flysystem\TempdirAdapter(Arr::get($config, 'prefix'), Arr::get($config, 'tempdir')), $config);
            });
        }
    }

    protected function buildMirrors($disks)
    {
        $main = $this->disk(Arr::first($disks))->getAdapter();

        if (count($disks) > 2) {
            $second = $this->buildMirrors(array_slice($disks, 1));
        } else {
            $second = $this->disk(Arr::last($disks))->getAdapter();
        }

        return new \League\Flysystem\Replicate\ReplicateAdapter(new \Litipk\Flysystem\Fallback\FallbackAdapter($main, $second, true), $second);
    }

    /**
     * Resolve the given disk.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function resolve($name)
    {
        $adapter = parent::resolve($name);

        if (class_exists('Twistor\FlysystemStreamWrapper')) {
            \Twistor\FlysystemStreamWrapper::register($name, $adapter->getDriver());
        }

        return $adapter;
    }
}
