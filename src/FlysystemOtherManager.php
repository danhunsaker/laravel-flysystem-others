<?php

namespace Danhunsaker\Laravel\Flysystem;

use Danhunsaker\Laravel\Flysystem\FlysystemManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlysystemOtherManager extends FlysystemManager
{
    /**
     * {@inheritdoc}
     */
    public function __construct($app)
    {
        parent::__construct($app);

        if (class_exists('\Mhetreramesh\Flysystem\BackblazeAdapter')) {
            $this->extend('backblaze', function ($app, $config) {
                return $this->createFlysystem(new \Mhetreramesh\Flysystem\BackblazeAdapter(new \BackblazeB2\Client($config['account_id'], $config['application_key']), $config['bucket']), $config);
            });
        }

        if (class_exists('\Zhuxiaoqiao\Flysystem\BaiduBos\BaiduBosAdapter')) {
            $this->extend('bos', function ($app, $config) {
                return $this->createFlysystem(new \Zhuxiaoqiao\Flysystem\BaiduBos\BaiduBosAdapter(new \BaiduBce\Services\Bos\BosClient(Arr::except($config, ['driver', 'bucket'])), $config['bucket']), $config);
            });
        }

        if (class_exists('\mgriego\Flysystem\ClamAV\ClamAvScannerAdapter')) {
            $this->extend('clamav', function ($app, $config) {
                return $this->createFlysystem(new \mgriego\Flysystem\ClamAV\ClamAvScannerAdapter(new \Xenolope\Quahog\Client((new \Socket\Raw\Factory())->createClient($config['server'])), $this->disk($config['drive'])->getAdapter(), Arr::get($config, 'copy_scan', false)), $config);
            });
        }

        if (class_exists('\CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter')) {
            $this->extend('cloudinary', function ($app, $config) {
                return $this->createFlysystem(new \CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter(Arr::except($config, ['driver'])), $config);
            });
        } elseif (class_exists('\Enl\Flysystem\Cloudinary\CloudinaryAdapter')) {
            $this->extend('cloudinary', function ($app, $config) {
                return $this->createFlysystem(new \Enl\Flysystem\Cloudinary\CloudinaryAdapter(new \Enl\Flysystem\Cloudinary\ApiFacade(Arr::except($config, ['driver']))), $config);
            });
        } elseif (class_exists('\T3chnik\FlysystemCloudinaryAdapter\CloudinaryAdapter')) {
            $this->extend('cloudinary', function ($app, $config) {
                return $this->createFlysystem(new \T3chnik\FlysystemCloudinaryAdapter\CloudinaryAdapter(Arr::except($config, ['driver']), new \Cloudinary\Api), $config);
            });
        }

        if (class_exists('\Spatie\FlysystemDropbox\DropboxAdapter')) {
            $this->extend('dropbox', function ($app, $config) {
                $client = new \Spatie\Dropbox\Client($config['authToken']);
                return $this->createFlysystem(new \Spatie\FlysystemDropbox\DropboxAdapter($client), $config);
            });
        } elseif (class_exists('\Srmklive\Dropbox\Adapter\DropboxAdapter')) {
            $this->extend('dropbox', function ($app, $config) {
                $client = new \Srmklive\Dropbox\Client\DropboxClient($config['authToken']);
                return $this->createFlysystem(new \Srmklive\Dropbox\Adapter\DropboxAdapter($client), $config);
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

        if (class_exists('\PrivateIT\FlySystem\GoogleDrive\GoogleDriveAdapter')) {
            $this->extend('gdrive', function ($app, $config) {
                $client = new \Google_Client();
                $client->setClientId($config['client_id']);
                $client->setClientSecret($config['secret']);
                $client->refreshToken($config['token']);

                $adapter = new \PrivateIT\FlySystem\GoogleDrive\GoogleDriveAdapter(new \Google_Service_Drive($client), Arr::get($config, 'root', null));
                $adapter->setPathManager(new GoogleSheetsPathManager(new \Google_Service_Sheets($client), Arr::get($config, $config, 'paths_sheet', null), $this->disk(Arr::get($config, 'paths_cache_drive', config('filesystems.default')))));

                return $this->createFlysystem($adapter, $config);
            });
        } elseif (class_exists('\Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter')) {
            $this->extend('gdrive', function ($app, $config) {
                $client = new \Google_Client();
                $client->setClientId($config['client_id']);
                $client->setClientSecret($config['secret']);
                $client->refreshToken($config['token']);

                return $this->createFlysystem(new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter(new \Google_Service_Drive($client), Arr::get($config, 'root', null)), $config);
            });
        } elseif (class_exists('\Ignited\Flysystem\GoogleDrive\GoogleDriveAdapter')) {
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

        if (class_exists('\Potherca\Flysystem\Github\GithubAdapter')) {
            $this->extend('github', function ($app, $config) {
                $settings = new \Potherca\Flysystem\Github\Settings($config['project'], [\Potherca\Flysystem\Github\Settings::AUTHENTICATE_USING_TOKEN, $config['token']]);

                return $this->createFlysystem(new \Potherca\Flysystem\Github\GithubAdapter(new \Potherca\Flysystem\Github\Api(new \Github\Client(), $settings)), $config);
            });
        }

        if (class_exists('\Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter')) {
            $this->extend('google', function ($app, $config) {
                return $this->createFlysystem(new \Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter(new \Google\Cloud\Storage\StorageClient([
                    'projectId' => $config['project_id'],
                    'keyFilePath' => Arr::get($config, 'key_file'),
                ]), $config['bucket']), $config);
            });
        } elseif (class_exists('\CedricZiel\FlysystemGcs\GoogleCloudStorageAdapter')) {
            $this->extend('google', function ($app, $config) {
                return $this->createFlysystem(new \CedricZiel\FlysystemGcs\GoogleCloudStorageAdapter(new \Google\Cloud\Storage\StorageClient([
                    'projectId' => $config['project_id'],
                    'keyFilePath' => Arr::get($config, 'key_file'),
                ]), Arr::only($config, ['bucket', 'prefix', 'url'])), $config);
            });
        }

        if (class_exists('\Twistor\Flysystem\GuzzleAdapter')) {
            $this->extend('http', function ($app, $config) {
                return $this->createFlysystem(new \Twistor\Flysystem\GuzzleAdapter($config['root']), $config);
            });
        } elseif (class_exists('\Twistor\Flysystem\Http\HttpAdapter')) {
            $this->extend('http', function ($app, $config) {
                return $this->createFlysystem(new \Twistor\Flysystem\Http\HttpAdapter($config['root'], Arr::get($config, 'use_head', true), Arr::get($config, 'context')), $config);
            });
        }

        if (class_exists('\Litipk\Flysystem\Fallback\FallbackAdapter') && class_exists('\League\Flysystem\Replicate\ReplicateAdapter')) {
            $this->extend('mirror', function ($app, $config) {
                return $this->createFlysystem($this->buildMirrors($config['disks']), $config);
            });
        }

        if (class_exists('\Ignited\Flysystem\OneDrive\OneDriveAdapter')) {
            $this->extend('onedrive', function ($app, $config) {
                $oneConfig = Arr::only($config, ['base_url', 'access_token']);
                if ($config['use_logger']) {
                    $logger = Log::getMonolog();
                } else {
                    $logger = null;
                }

                return $this->createFlysystem(new \Ignited\Flysystem\OneDrive\OneDriveAdapter(\Ignited\Flysystem\OneDrive\OneDriveClient::factory($oneConfig, $logger)), $config);
            });
        } elseif (class_exists('\JacekBarecki\FlysystemOneDrive\Adapter\OneDriveAdapter')) {
            $this->extend('onedrive', function ($app, $config) {
                return $this->createFlysystem(new \JacekBarecki\FlysystemOneDrive\Adapter\OneDriveAdapter(new \JacekBarecki\FlysystemOneDrive\Client\OneDriveClient(Arr::get($config, 'access_token'), new \GuzzleHttp\Client())), $config);
            });
        } elseif (class_exists('\NicolasBeauvais\FlysystemOneDrive\OneDriveAdapter')) {
            $this->extend('onedrive', function ($app, $config) {
                $graph = new \Microsoft\Graph\Graph();
                $graph->setAccessToken($config['access_token']);

                return $this->createFlysystem(new \NicolasBeauvais\FlysystemOneDrive\OneDriveAdapter($graph, Arr::get($config, 'root', 'root')), $config);
            });
        }

        if (class_exists('\Nimbusoft\Flysystem\OpenStack\SwiftAdapter')) {
            $this->extend('openstack', function ($app, $config) {
                $container = (new \OpenStack\OpenStack([
                    'authUrl' => $config['auth_url'],
                    'region'  => $config['region'],
                    'user'    => [
                        'id'       => $config['user_id'],
                        'password' => $config['password']
                    ],
                    'scope'   => ['project' => ['id' => $config['project_id']]]
                ]))->objectStoreV1()->getContainer($config['container']);

                return $this->createFlysystem(new \Nimbusoft\Flysystem\OpenStack\SwiftAdapter($container), $config);
            });
        }

        if (class_exists('\Aliyun\Flysystem\AliyunOss\AliyunOssAdapter')) {
            $this->extend('oss', function ($app, $config) {
                return $this->createFlysystem(new \Aliyun\Flysystem\AliyunOss\AliyunOssAdapter(new \OSS\OSSClient(Arr::get($config, 'access_id'), Arr::get($config, 'access_key'), Arr::get($config, 'endpoint')), Arr::get($config, 'bucket')), $config);
            });
        } elseif (class_exists('\Aobo\OSS\AliyunOssAdapter')) {
            $this->extend('oss', function ($app, $config) {
                return $this->createFlysystem(new \Aobo\OSS\AliyunOssAdapter(new \OSS\OSSClient(Arr::get($config, 'access_id'), Arr::get($config, 'access_key'), Arr::get($config, 'endpoint')), Arr::get($config, 'bucket')), $config);
            });
        } elseif (class_exists('\ApolloPY\Flysystem\AliyunOss')) {
            $this->extend('oss', function ($app, $config) {
                return $this->createFlysystem(new \ApolloPY\Flysystem\AliyunOss(new \OSS\OSSClient(Arr::get($config, 'access_id'), Arr::get($config, 'access_key'), Arr::get($config, 'endpoint')), Arr::get($config, 'bucket'), Arr::get($config, 'prefix')), $config);
            });
        } elseif (class_exists('\Orzcc\AliyunOss\AliyunOssAdapter')) {
            $this->extend('oss', function ($app, $config) {
                $ossconfig = [
                    'AccessKeyId'     => $config['access_id'],
                    'AccessKeySecret' => $config['access_key']
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
        } elseif (class_exists('\Xxtime\Flysystem\Aliyun\OssAdapter')) {
            $this->extend('oss', function ($app, $config) {
                $ossconfig = [
                    'access_id'     => $config['access_id'],
                    'access_secret' => $config['access_key'],
                    'bucket'        => $config['bucket'],
                ];

                if (isset($config['endpoint']) && ! empty($config['endpoint'])) {
                    $ossconfig['endpoint'] = $config['endpoint'];
                }

                return $this->createFlysystem(new \Xxtime\Flysystem\Aliyun\OssAdapter($ossconfig), $config);
            });
        } elseif (class_exists('\League\Flysystem\AliyunOSS\AliyunOSSAdapter')) {
        // NOT ACTUALLY A LEAGUE PROJECT!!!
            $this->extend('oss', function ($app, $config) {
                return $this->createFlysystem(new \League\Flysystem\AliyunOSS\AliyunOSSAdapter(new \ALIOSS(Arr::get($config, 'access_id'), Arr::get($config, 'access_key'), Arr::get($config, 'endpoint')), Arr::get($config, 'bucket')), $config);
            });
        }

        if (class_exists('\Integral\Flysystem\Adapter\PDOAdapter')) {
            $this->extend('pdo', function ($app, $config) {
                return $this->createFlysystem(
                    new \Integral\Flysystem\Adapter\PDOAdapter(
                        \DB::connection($config['database'])->getPdo(),
                        $config['table'],
                        array_key_exists('table_prefix', $config) ? $config['table_prefix'] : null
                    ),
                    $config
                );
            });
        } elseif (class_exists('\Phlib\Flysystem\Pdo\PdoAdapter')) {
            $this->extend('pdo', function ($app, $config) {
                $driverConfig = new \League\Flysystem\Config(Arr::only($config, [
                    'visbility',
                    'table_prefix',
                    'enable_compression',
                    'chunk_size',
                    'temp_dir',
                    'disable_mysql_buffering',
                ]));

                return $this->createFlysystem(new \Phlib\Flysystem\Pdo\PdoAdapter(DB::connection($config['database'])->getPdo(), $driverConfig), $config);
            });
        }

        if (class_exists('\Freyo\Flysystem\QcloudCOSv5\Adapter')) {
            $this->extend('qcloud', function ($app, $config) {
                $cosconfig = [
                    'region'          => $config['region'],
                    'credentials'     => [
                        'appId'     => $config['app_id'],
                        'secretId'  => $config['secret_id'],
                        'secretKey' => $config['secret_key'],
                    ],
                    'timeout'         => $config['timeout'],
                    'bucket'          => $config['bucket'],
                    'scheme'          => $config['protocol'],
                ];

                if (array_key_exists('domain', $config)) {
                    $cosconfig['cdn'] = $config['domain'];
                }

                return $this->createFlysystem(new \Freyo\Flysystem\QcloudCOSv5\Adapter($cosconfig), $config);
            });
        } elseif (class_exists('\Freyo\Flysystem\QcloudCOSv4\Adapter')) {
            $this->extend('qcloud', function ($app, $config) {
                return $this->createFlysystem(new \Freyo\Flysystem\QcloudCOSv4\Adapter(Arr::except($config, ['driver'])), $config);
            });
        } elseif (class_exists('\Freyo\Flysystem\QcloudCOSv3\Adapter')) {
            $this->extend('qcloud', function ($app, $config) {
                return $this->createFlysystem(new \Freyo\Flysystem\QcloudCOSv3\Adapter(Arr::except($config, ['driver', 'region'])), $config);
            });
        }

        if (class_exists('\Boofw\Flysystem\Qiniu\QiniuAdapter')) {
            $this->extend('qiniu', function ($app, $config) {
                return $this->createFlysystem(new \Boofw\Flysystem\Qiniu\QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket']), $config);
            });
        } elseif (class_exists('\EQingdan\Flysystem\Qiniu\QiniuAdapter')) {
            $this->extend('qiniu', function ($app, $config) {
                return $this->createFlysystem(new \EQingdan\Flysystem\Qiniu\QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket'], $config['domain']), $config);
            });
        } elseif (class_exists('\Overtrue\Flysystem\Qiniu\QiniuAdapter')) {
            $this->extend('qiniu', function ($app, $config) {
                return $this->createFlysystem(new \Overtrue\Flysystem\Qiniu\QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket'], $config['domain']), $config);
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

        if (class_exists('\ArgentCrusade\Flysystem\Selectel\SelectelAdapter')) {
            $this->extend('selectel', function ($app, $config) {
                $storage = new ArgentCrusade\Selectel\CloudStorage\CloudStorage(new ArgentCrusade\Selectel\CloudStorage\Api\ApiClient($config['username'], $config['password']));
                $container = $storage->getContainer($config['container']);

                if (isset($config['domain'])) {
                    $container->setUrl($config['domain']);
                }

                return $this->createFlysystem(new \ArgentCrusade\Flysystem\Selectel\SelectelAdapter($container), $config);
            });
        }

        if (class_exists('\Kapersoft\FlysystemSharefile\SharefileAdapter')) {
            $this->extend('sharefile', function ($app, $config) {
                return $this->createFlysystem(new \Kapersoft\FlysystemSharefile\SharefileAdapter(new Kapersoft\Sharefile\Client($config['hostname'], $config['client_id'], $config['secret'], $config['username'], $config['password'])), $config);
            });
        }

        if (class_exists('\RobGridley\Flysystem\Smb\SmbAdapter')) {
            $this->extend('smb', function ($app, $config) {
                if (class_exists('\Icewind\SMB\Server')) {
                    $server = new \Icewind\SMB\Server($config['host'], $config['username'], $config['password']);
                } elseif (class_exists('\Icewind\SMB\ServerFactory')) {
                    $server = with(new \Icewind\SMB\ServerFactory)->createServer($config['host'], new \Icewind\SMB\BasicAuth($config['username'], $config['workgroup'], $config['password']));
                }
                $share = $server->getShare($config['path']);

                return $this->createFlysystem(new \RobGridley\Flysystem\Smb\SmbAdapter($share), $config);
            });
        }

        if (class_exists('\Emgag\Flysystem\TempdirAdapter')) {
            $this->extend('temp', function ($app, $config) {
                return $this->createFlysystem(new \Emgag\Flysystem\TempdirAdapter(Arr::get($config, 'prefix'), Arr::get($config, 'tempdir')), $config);
            });
        }

        if (class_exists('\JellyBool\Flysystem\Upyun\UpyunAdapter')) {
            $this->extend('upyun', function ($app, $config) {
                return $this->createFlysystem(new \JellyBool\Flysystem\Upyun\UpyunAdapter($config['bucket'], $config['operator'], $config['password'], $config['domain'], Arr::get($config, 'protocol', 'https')), $config);
            });
        }

        if (class_exists('\Arhitector\Yandex\Disk\Adapter\Flysystem')) {
            $this->extend('yandex', function ($app, $config) {
                return $this->createFlysystem(new \Arhitector\Yandex\Disk\Adapter\Flysystem(new \Arhitector\Yandex\Disk($config['access_token']), Arr::get($config, 'prefix', 'app:/')), $config);
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
     * {@inheritdoc}
     */
    protected function resolve($name, $config = null)
    {
        $adapter = parent::resolve($name, $config);

        if (class_exists('Twistor\FlysystemStreamWrapper')) {
            \Twistor\FlysystemStreamWrapper::register($name, $adapter->getDriver());
        }

        return $adapter;
    }
}
