# Laravel Flysystem Others #

[![Software License](https://img.shields.io/packagist/l/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](LICENSE)
[![Gitter](https://img.shields.io/gitter/room/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://gitter.im/danhunsaker/laravel-flysystem-others)
[![Liberapay receiving](https://img.shields.io/liberapay/receives/danhunsaker.svg?style=flat-square)](https://liberapay.com/danhunsaker/)

[![Latest Stable Version](https://img.shields.io/packagist/v/danhunsaker/laravel-flysystem-others.svg?label=stable&style=flat-square)](https://github.com/danhunsaker/laravel-flysystem-others/releases)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/danhunsaker/laravel-flysystem-others.svg?label=unstable&style=flat-square)](https://github.com/danhunsaker/laravel-flysystem-others)
[![Build Status](https://img.shields.io/travis/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://travis-ci.org/danhunsaker/laravel-flysystem-others)
[![Codecov](https://img.shields.io/codecov/c/github/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://codecov.io/gh/danhunsaker/laravel-flysystem-others)
[![Total Downloads](https://img.shields.io/packagist/dt/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://packagist.org/packages/danhunsaker/laravel-flysystem-others)

Registers recognized third-party Flysystem adapters with Laravel automatically.

This lets you use third-party adapters without having to write your own service
providers to load them properly.  It automatically detects which adapters are
available, and registers only the ones actually installed.  It also detects
whether the [Eventable][] version of Flysystem is available, and if so, it
switches to it, letting you listen in on Flysystem [events][] and affect them
accordingly.

> NOTE: While this package only recognizes adapters NOT officially supported by
> [The PHP League][], it *does* depend on
> [danhunsaker/laravel-flysystem-service][], so installing this package *will*
> let you use them as well.

## Installation ##

The usual methods for using [Composer][] apply here:

    composer require danhunsaker/laravel-flysystem-others

This package uses Laravel's auto-discovery feature for the service provider, but
if you're using a Laravel version before 5.5, you do still have to register one
service – but only one, and at least you don't have to _write_ it. Be sure to
**REPLACE** the `Illuminate\Filesystem\FilesystemServiceProvider::class` line
with the new one:

```php
// In config/app.php

    'providers' => [
        // ...
        Danhunsaker\Laravel\Flysystem\FlysystemOtherServiceProvider::class,
        // ...
    ],
```

And since `FlysystemOtherServiceProvider` extends `FlysystemServiceProvider`
from `danhunsaker/laravel-flysystem-service`, you don't need to add it as well.
In fact, doing so will probably cause some issues with your app, as both
providers will attempt to handle the PHP League drivers at the same time.

Of course, you'll want to read through its `README` as well, to see what options
it supports that this package piggy-backs on, such as those for the cache
decorator.

## Setup ##

For added flexibility, such as the ability to open ZIP files on remote storage,
you can also install [twistor/flysystem-stream-wrapper][], which will register
each of the drives in your `config/filesystems.php` file as a stream protocol
(though only when each is accessed the first time, unless you add them to the
`autowrap` parameter in the configuration).  In the example of accessing remote
ZIP files, you would then simply need to prefix the ZIP file's path with the
name of the drive it's available on, as a URL scheme (something like
`dropbox://path/to/file.zip`).

Finally, as with `danhunsaker/laravel-flysystem-service`, you can get example
definitions for all supported filesystem drivers by publishing the replacement
`filesystems` config - just run the following Artisan command:

    php artisan vendor:publish --provider=Danhunsaker\\Laravel\\Flysystem\\FlysystemOtherServiceProvider --force

The `--force` flag is required to overwrite the existing `filesystems` config
that ships with Laravel.  You can also rename the existing file, then run the
command without the `--force` flag, if you'd like to preserve the existing
contents for transfer to the new file.

## Supported Adapters ##

The best place to check for which adapters are supported by this package is the
Composer suggestions, but here's a quick (not-guaranteed-up-to-date) list as
well:

-   Aliyun OSS:
    [aliyuncs/aliyun-oss-flysystem][],
    [aobozhang/aliyun-oss-adapter][],
    [apollopy/flysystem-aliyun-oss][],
    [orzcc/aliyun-oss][],
    [shion/aliyun-oss][],
    [xxtime/flysystem-aliyun-oss][],
    [monster/flysystem-aliyun-oss][] (only used if no other `oss` adapter is
    available, because it uses the `League` namespace, but isn't a PHP League
    package),

-   Backblaze B2:
    [mhetreramesh/flysystem-backblaze][]

-   Baidu Bos:
    [zhuxiaoqiao/flysystem-baidu-bos][]

-   Citrix ShareFile:
    [kapersoft/flysystem-sharefile][]

-   ClamAV (Virus Scanning):
    [mgriego/flysystem-clamav][]

-   Cloudinary:
    [carlosocarvalho/flysystem-cloudinary][],
    [enl/flysystem-cloudinary][],
    [t3chnik/flysystem-cloudinary-adapter][]

-   Eloquent:
    [rokde/flysystem-local-database-adapter][]

-   Fallback:
    [litipk/flysystem-fallback-adapter][]

-   GitHub:
    [potherca/flysystem-github][]

-   Google Cloud Storage:
    [cedricziel/flysystem-gcs][],
    [superbalist/flysystem-google-storage][]

-   Google Drive:
    [ignited/flysystem-google-drive][],
    [nao-pon/flysystem-google-drive][],
    [private-it/flysystem-google-drive][] (has precendence over `nao-pon`)

-   HTTP (Read-Only):
    [twistor/flysystem-guzzle][],
    [twistor/flysystem-http][]

-   Mirror:
    A "meta-adapter" which combines the Fallback and Replicate adapters, if both
    are available.

-   OneDrive:
    [ignited/flysystem-onedrive][],
    [jacekbarecki/flysystem-onedrive][],
    [nicolasbeauvais/flysystem-onedrive][]

-   OpenStack Swift:
    [nimbusoft/flysystem-openstack-swift][]

-   PDO:
    [integral/flysystem-pdo-adapter][],
    [phlib/flysystem-pdo][]

-   Qcloud COS:
    [freyo/flysystem-qcloud-cos-v5][],
    [freyo/flysystem-qcloud-cos-v4][],
    [freyo/flysystem-qcloud-cos-v3][]
    (precendence for this adapter is determined by API version, rather than
    alphabetical sort)

-   Qiniu:
    [boofw/flysystem-qiniu][],
    [eqingdan/flysystem-qiniu][],
    [overtrue/flysystem-qiniu][]

-   Redis:
    [danhunsaker/flysystem-redis][]

-   Runabove:
    [engineor/flysystem-runabove][]

-   Selectel:
    [argentcrusade/flysystem-selectel][]

-   SMB/CIFS:
    [robgridley/flysystem-smb][]

-   Temp:
    [emgag/flysystem-tempdir][]

-   Upyun:
    [jellybool/flysystem-upyun][]

-   Yandex:
    [arhitector/yandex-disk-flysystem][]

> NOTE: If you install more than one of the adapters listed above for the same
> storage service, only the first one – in alphabetical order by namespace –
> will be used, unless otherwise noted above.

## Contributions ##

Pull requests, bug reports, and so forth are all welcome on [GitHub][].

Security issues should be reported directly to [danhunsaker (plus) laraflyplus
(at) gmail (dot) com](mailto:danhunsaker+laraflyplus@gmail.com).

And head to [GitHub][] for everything else.

[aliyuncs/aliyun-oss-flysystem]: https://packagist.org/packages/
[aobozhang/aliyun-oss-adapter]: https://packagist.org/packages/aobozhang/aliyun-oss-adapter
[apollopy/flysystem-aliyun-oss]: https://packagist.org/packages/apollopy/flysystem-aliyun-oss
[argentcrusade/flysystem-selectel]: https://packagist.org/packages/argentcrusade/flysystem-selectel
[mhetreramesh/flysystem-backblaze]: https://packagist.org/packages/mhetreramesh/flysystem-backblaze
[boofw/flysystem-qiniu]: https://packagist.org/packages/boofw/flysystem-qiniu
[carlosocarvalho/flysystem-cloudinary]: https://packagist.org/packages/carlosocarvalho/flysystem-cloudinary
[cedricziel/flysystem-gcs]: https://packagist.org/packages/cedricziel/flysystem-gcs
[coldwind/flysystem-sae]: https://packagist.org/packages/coldwind/flysystem-sae
[composer]: https://getcomposer.org
[danhunsaker/flysystem-redis]: https://packagist.org/packages/danhunsaker/flysystem-redis
[danhunsaker/laravel-flysystem-service]: https://github.com/danhunsaker/laravel-flysystem-service
[emgag/flysystem-tempdir]: https://packagist.org/packages/emgag/flysystem-tempdir
[engineor/flysystem-runabove]: https://packagist.org/packages/engineor/flysystem-runabove
[enl/flysystem-cloudinary]: https://packagist.org/packages/enl/flysystem-cloudinary
[eqingdan/flysystem-qiniu]: https://packagist.org/packages/eqingdan/flysystem-qiniu
[eventable]: https://github.com/thephpleague/flysystem-eventable-filesystem
[events]: http://event.thephpleague.com/
[freyo/flysystem-qcloud-cos-v3]: https://packagist.org/packages/freyo/flysystem-qcloud-cos-v3
[freyo/flysystem-qcloud-cos-v4]: https://packagist.org/packages/freyo/flysystem-qcloud-cos-v4
[freyo/flysystem-qcloud-cos-v5]: https://packagist.org/packages/freyo/flysystem-qcloud-cos-v5
[github]: https://github.com/danhunsaker/laravel-flysystem-others
[ignited/flysystem-google-drive]: https://packagist.org/packages/ignited/flysystem-google-drive
[ignited/flysystem-onedrive]: https://packagist.org/packages/ignited/flysystem-onedrive
[integral/flysystem-pdo-adapter]: https://packagist.org/packages/integral/flysystem-pdo-adapter
[jacekbarecki/flysystem-onedrive]: https://packagist.org/packages/jacekbarecki/flysystem-onedrive
[arhitector/yandex-disk-flysystem]: https://packagist.org/packages/arhitector/yandex-disk-flysystem
[jellybool/flysystem-upyun]: https://packagist.org/packages/jellybool/flysystem-upyun
[kapersoft/flysystem-sharefile]: https://packagist.org/packages/kapersoft/flysystem-sharefile
[litipk/flysystem-fallback-adapter]: https://packagist.org/packages/litipk/flysystem-fallback-adapter
[mgriego/flysystem-clamav]: https://packagist.org/packages/mgriego/flysystem-clamav
[monster/flysystem-aliyun-oss]: https://packagist.org/packages/
[nao-pon/flysystem-google-drive]: https://packagist.org/packages/nao-pon/flysystem-google-drive
[nicolasbeauvais/flysystem-onedrive]: https://packagist.org/packages/nicolasbeauvais/flysystem-onedrive
[nimbusoft/flysystem-openstack-swift]: https://packagist.org/packages/nimbusoft/flysystem-openstack-swift
[orzcc/aliyun-oss]: https://packagist.org/packages/orzcc/aliyun-oss
[overtrue/flysystem-qiniu]: https://packagist.org/packages/overtrue/flysystem-qiniu
[phlib/flysystem-pdo]: https://packagist.org/packages/phlib/flysystem-pdo
[potherca/flysystem-github]: https://packagist.org/packages/potherca/flysystem-github
[private-it/flysystem-google-drive]: https://packagist.org/packages/private-it/flysystem-google-drive
[robgridley/flysystem-smb]: https://packagist.org/packages/robgridley/flysystem-smb
[rokde/flysystem-local-database-adapter]: https://packagist.org/packages/rokde/flysystem-local-database-adapter
[shion/aliyun-oss]: https://packagist.org/packages/shion/aliyun-oss
[superbalist/flysystem-google-storage]: https://packagist.org/packages/superbalist/flysystem-google-storage
[t3chnik/flysystem-cloudinary-adapter]: https://packagist.org/packages/t3chnik/flysystem-cloudinary-adapter
[the php league]: https://github.com/thephpleague?query=flysystem
[twistor/flysystem-guzzle]: https://packagist.org/packages/twistor/flysystem-guzzle
[twistor/flysystem-http]: https://packagist.org/packages/twistor/flysystem-http
[twistor/flysystem-stream-wrapper]: https://packagist.org/packages/twistor/flysystem-stream-wrapper
[xxtime/flysystem-aliyun-oss]: https://packagist.org/packages/xxtime/flysystem-aliyun-oss
[zhuxiaoqiao/flysystem-baidu-bos]: https://packagist.org/packages/zhuxiaoqiao/flysystem-baidu-bos
