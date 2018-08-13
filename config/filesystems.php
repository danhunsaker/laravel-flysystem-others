<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported: "local", "ftp", "s3", "rackspace", "null", "azure", "copy",
    |            "dropbox", "gridfs", "memory", "phpcr", "replicate", "sftp",
    |            "vfs", "webdav", "zip", "bos", "cloudinary", "eloquent",
    |            "fallback", "github", "gdrive", "google", "mirror", "onedrive",
    |            "oss", "qiniu", "redis", "runabove", "sae", "smb", "temp"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'ftp' => [
            'driver'   => 'ftp',
            'host'     => 'ftp.example.com',
            'username' => 'your-username',
            'password' => 'your-password',

            // Optional FTP Settings...
            // 'port'     => 21,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),

            // Optional cache settings, available with any storage driver
            'cache'       => [
                'driver' => 'laravel',
            ],
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'username'  => 'your-username',
            'key'       => 'your-key',
            'region'    => 'IAD',
            'url_type'  => 'publicURL',
            'container' => 'your-container',
        ],

        'null' => [
            'driver' => 'null',
        ],

        'azure' => [
            'driver'      => 'azure',
            'accountName' => 'your-account-name',
            'apiKey'      => 'your-api-key',
            'container'   => 'your-container',
        ],

        'gridfs' => [
            'driver'  => 'gridfs',
            'server'  => 'your-server',
            'context' => 'your-context',
            'dbName'  => 'your-db-name',

            // You can also provide other MongoDB connection options here
        ],

        'memory' => [
            'driver' => 'memory',
        ],

        'phpcr-jackrabbit' => [
            'driver'         => 'phpcr',
            'jackrabbit_url' => 'your-jackrabbit-url',
            'workspace'      => 'your-workspace',
            'root'           => 'your-root',

            // Optional PHPCR Settings
            // 'userId'         => 'your-user-id',
            // 'password'       => 'your-password',
        ],

        'phpcr-dbal' => [
            'driver'    => 'phpcr',
            'database'  => 'mysql',
            'workspace' => 'your-workspace',
            'root'      => 'your-root',

            // Optional PHPCR Settings
            // 'userId'    => 'your-user-id',
            // 'password'  => 'your-password',
        ],

        'phpcr-prismic' => [
            'driver'      => 'phpcr',
            'prismic_uri' => 'your-prismic-uri',
            'workspace'   => 'your-workspace',
            'root'        => 'your-root',

            // Optional PHPCR Settings
            // 'userId'      => 'your-user-id',
            // 'password'    => 'your-password',
        ],

        'replicate' => [
            'driver'  => 'replicate',
            'master'  => 'local',
            'replica' => 's3',
        ],

        'sftp' => [
            'driver'        => 'sftp',
            'host'          => 'sftp.example.com',
            'username'      => 'username',
            'password'      => 'password',

            // Optional SFTP Settings
            // 'privateKey'    => 'path/to/or/contents/of/privatekey',
            // 'port'          => 22,
            // 'root'          => '/path/to/root',
            // 'timeout'       => 30,
            // 'directoryPerm' => 0755,
            // 'permPublic'    => 0644,
            // 'permPrivate'   => 0600,
        ],

        'vfs' => [
            'driver' => 'vfs',
        ],

        'webdav' => [
            'driver'   => 'webdav',
            'baseUri'  => 'http://example.org/dav/',

            // Optional WebDAV Settings
            // 'userName' => 'user',
            // 'password' => 'password',
            // 'proxy'    => 'locahost:8888',
            // 'authType' => 'digest',  // alternately 'ntlm' or 'basic'
            // 'encoding' => 'all',     // same as ['deflate', 'gzip', 'identity']
        ],

        'zip' => [
            'driver' => 'zip',
            'path'   => 'path/to/file.zip',

            // Alternate value if twistor/flysystem-stream-wrapper is available
            // 'path'   => 'local://path/to/file.zip',
        ],

        'bos' => [
            'driver'      => 'bos',
            'credentials' => [
                'ak' => 'your-access-key-id',
                'sk' => 'your-secret-access-key',
            ],
            'bucket'      => 'your-bucket',

            // Optional BOS Setting
            // 'endpoint'    => 'http://bj.bcebos.com',
        ],

        'cloudinary' => [
            'driver'     => 'cloudinary',
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        ],

        'dropbox' => [
            'driver'           => 'dropbox',
            'authToken'      => 'your-auth-token',
        ],

        'eloquent' => [
            'driver' => 'eloquent',

            // Optional Eloquent Setting
            // 'model'  => '\Rokde\Flysystem\Adapter\Model\FileModel',
        ],

        'fallback' => [
            'driver'   => 'fallback',
            'main'     => 'local',
            'fallback' => 's3',
        ],

        'github' => [
            'driver'  => 'github',
            'project' => 'yourname/project',
            'token'   => 'your-github-token',
        ],

        'gdrive' => [
            'driver'    => 'gdrive',
            'client_id' => 'your-client-id',
            'secret'    => 'your-secret',
            'token'     => 'your-token',
        ],

        'google' => [
            'driver'        => 'google',
            'account'       => 'your-account',
            'secret'        => 'your-secret',
            'developer_key' => 'your-developer-key',
            'p12_file'      => 'local://path/to/file.p12',
            'bucket'        => 'your-bucket',
        ],

        'mirror' => [
            'driver' => 'mirror',
            'disks'  => ['local', 's3', 'zip'],
        ],

        'onedrive' => [
            'driver'       => 'onedrive',
            'access_token' => 'your-access-token',

            // Options only needed for ignited/flysystem-onedrive
            // 'base_url'     => 'https://api.onedrive.com/v1.0/',
            // 'use_logger'   => false,
        ],

        'oss' => [
            'driver'     => 'oss',
            'access_id'  => 'your-access-id',
            'access_key' => 'your-access-key',
            'bucket'     => 'your-bucket',

            // Optional OSS Settings
            // 'endpoint'   => '',
            // 'prefix'     => '',
            // 'region'     => '',    // One of 'hangzhou', 'qingdao', 'beijing', 'hongkong',
            //                        // 'shenzhen', 'shanghai', 'west-1' and 'southeast-1'
        ],

        'qiniu' => [
            'driver'    => 'qiniu',
            'accessKey' => 'your-access-key',
            'secretKey' => 'your-secret-key',
            'bucket'    => 'your-bucket',

            // Optional Qiniu Settings
            // 'domain'    => '',
        ],

        'redis' => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],

        'runabove' => [
            'driver'    => 'runabove',
            'username'  => 'your-username',
            'password'  => 'your-password',
            'tenantId'  => 'your-tenantId',

            // Optional Runabove Settings
            // 'container' => 'container',
            // 'region'    => 'SBG1',   // One of 'SBG1', 'BHS1' and 'GRA1'
        ],

        'sae' => [
            'driver' => 'sae',
        ],

        'smb' => [
            'driver'   => 'smb',
            'host'     => 'smb.example.com',
            'username' => 'your-username',
            'password' => 'your-password',
            'path'     => 'path/to/shared/directory/for/root',
        ],

        'temp' => [
            'driver'  => 'temp',

            // Optional TempDir Settings
            // 'prefix'  => '',
            // 'tempdir' => '/tmp',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Automatically Register Stream Wrappers
    |--------------------------------------------------------------------------
    |
    | This is a list of the filesystem "disks" to automatically register the
    | stream wrappers for on application start.  Any "disk" you don't want to
    | register on every application load will have to be manually referenced
    | before attempting stream access, as the stream wrapper is otherwise only
    | registered when used.
    |
    */

    'autowrap' => [

        'local',

    ],

];
