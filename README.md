# Laravel Flysystem Others #

[![Software License](https://img.shields.io/packagist/l/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](LICENSE)
[![Gitter](https://img.shields.io/gitter/room/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://gitter.im/danhunsaker/laravel-flysystem-others)

[![Latest Stable Version](https://img.shields.io/packagist/v/danhunsaker/laravel-flysystem-others.svg?label=stable&style=flat-square)](https://github.com/danhunsaker/laravel-flysystem-others/releases)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/danhunsaker/laravel-flysystem-others.svg?label=unstable&style=flat-square)](https://github.com/danhunsaker/laravel-flysystem-others)
[![Build Status](https://img.shields.io/travis/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://travis-ci.org/danhunsaker/laravel-flysystem-others)
[![Codecov](https://img.shields.io/codecov/c/github/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://codecov.io/gh/danhunsaker/laravel-flysystem-others)
[![Total Downloads](https://img.shields.io/packagist/dt/danhunsaker/laravel-flysystem-others.svg?style=flat-square)](https://packagist.org/packages/danhunsaker/laravel-flysystem-others)

Registers recognized third-party Flysystem adapters with Laravel automatically.

This lets you use third-party adapters without having to write your own service
providers to load them properly.  It automatically detects which adapters are
available, and registers only the ones actually installed.  It also detects
whether the [Eventable](https://github.com/thephpleague/flysystem-eventable-filesystem)
version of Flysystem is available, and if so, it switches to it, letting you
listen in on Flysystem [events](http://event.thephpleague.com/) and affect them
accordingly.

> Note: While this package only recognizes adapters NOT officially supported by
> [The PHP League](https://github.com/thephpleague?query=flysystem), it *does*
> depend on [danhunsaker/laravel-flysystem-service](https://github.com/danhunsaker/laravel-flysystem-service),
> so installing this package *will* let you use them as well.

## Installation ##

The usual methods for using [Composer](https://getcomposer.org) apply here:

    composer require danhunsaker/laravel-flysystem-others

You do still have to register one service, but only one, and at least you don't
have to write it:

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

## Setup ##

For added flexibility, such as the ability to open ZIP files on remote storage,
you can also install [twistor/flysystem-stream-wrapper](https://packagist.org/packages/twistor/flysystem-stream-wrapper),
which will register each of the drives in your `config/filesystems.php` file as
a stream protocol (though only when each is accessed the first time, unless you
add them to the `autowrap` parameter in the configuration).  In the example of
accessing remote ZIP files, you would then simply need to prefix the ZIP file's
path with the name of the drive it's available on, as a URL scheme (something
like `dropbox://path/to/file.zip`).

Finally, as with `danhunsaker/laravel-flysystem-service`, you can get example
definitions for all supported filesystem drivers by publishing the replacement
`filesystems` config - just run the following Artisan command:

```
php artisan vendor:publish --provider=Danhunsaker\\Laravel\\Flysystem\\FlysystemOtherServiceProvider --force
```

The `--force` flag is required to overwrite the existing `filesystems` config
that ships with Laravel.  You can also rename the existing file, then run the
command without the `--force` flag, if you'd like to preserve the existing
contents for transfer to the new file.

## Supported Adapters ##

The best place to check for which adapters are supported by this package is the
Composer suggestions, but here's a quick (not-guaranteed-up-to-date) list as
well:

- Aliyun OSS:
  [orzcc/aliyun-oss](https://packagist.org/packages/orzcc/aliyun-oss)
  or [shion/aliyun-oss](https://packagist.org/packages/shion/aliyun-oss)
- Baidu Bos:
  [zhuxiaoqiao/flysystem-baidu-bos](https://packagist.org/packages/zhuxiaoqiao/flysystem-baidu-bos)
- Cloudinary:
  [enl/flysystem-cloudinary](https://packagist.org/packages/enl/flysystem-cloudinary)
  or [t3chnik/flysystem-cloudinary-adapter](https://packagist.org/packages/t3chnik/flysystem-cloudinary-adapter)
- Eloquent:
  [rokde/flysystem-local-database-adapter](https://packagist.org/packages/rokde/flysystem-local-database-adapter)
- Fallback:
  [litipk/flysystem-fallback-adapter](https://packagist.org/packages/litipk/flysystem-fallback-adapter)
- GitHub:
  [potherca/flysystem-github](https://packagist.org/packages/potherca/flysystem-github)
- Google Cloud Storage:
  [superbalist/flysystem-google-storage](https://packagist.org/packages/superbalist/flysystem-google-storage)
- Google Drive:
  [ignited/flysystem-google-drive](https://packagist.org/packages/ignited/flysystem-google-drive)
- Mirror:
  A "meta-adapter" which combines the Fallback and Replicate adapters,
  if both are available.
- OneDrive:
  [jacekbarecki/flysystem-onedrive](https://packagist.org/packages/jacekbarecki/flysystem-onedrive)
  or [ignited/flysystem-onedrive](https://packagist.org/packages/ignited/flysystem-onedrive)
- Qiniu:
  [eqingdan/flysystem-qiniu](https://packagist.org/packages/eqingdan/flysystem-qiniu)
  or [polev/flysystem-qiniu](https://packagist.org/packages/polev/flysystem-qiniu)
- Redis:
  [danhunsaker/flysystem-redis](https://packagist.org/packages/danhunsaker/flysystem-redis)
- Runabove:
  [engineor/flysystem-runabove](https://packagist.org/packages/engineor/flysystem-runabove)
- Sae:
  [coldwind/flysystem-sae](https://packagist.org/packages/coldwind/flysystem-sae)
- SMB/CIFS:
  [robgridley/flysystem-smb](https://packagist.org/packages/robgridley/flysystem-smb)
- Temp:
  [emgag/flysystem-tempdir](https://packagist.org/packages/emgag/flysystem-tempdir)

## Issues, Contributions, Etc ##

Pull requests, bug reports, and so forth are all welcome on [GitHub][].

Security issues should be reported directly to [danhunsaker (plus) laraflyplus
(at) gmail (dot) com](mailto:danhunsaker+laraflyplus@gmail.com).

And head to [GitHub][] for everything else.

[GitHub]:https://github.com/danhunsaker/laravel-flysystem-others
