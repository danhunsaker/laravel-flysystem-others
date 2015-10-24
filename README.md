## Laravel Flysystem Others

[![Total Downloads](https://poser.pugx.org/danhunsaker/laravel-flysystem-others/d/total.svg)](https://packagist.org/packages/danhunsaker/laravel-flysystem-others)
[![Latest Stable Version](https://poser.pugx.org/danhunsaker/laravel-flysystem-others/v/stable.svg)](https://packagist.org/packages/danhunsaker/laravel-flysystem-others)
[![Latest Unstable Version](https://poser.pugx.org/danhunsaker/laravel-flysystem-others/v/unstable.svg)](https://packagist.org/packages/danhunsaker/laravel-flysystem-others)
[![License](https://poser.pugx.org/danhunsaker/laravel-flysystem-others/license.svg)](https://packagist.org/packages/danhunsaker/laravel-flysystem-others)

Registers recognized third-party Flysystem adapters with Laravel automatically.

This lets you use third-party adapters without having to write your own service providers to load them properly.  It automatically detects which adapters are available, and registers only the ones actually installed.  It also detects whether the [Eventable](https://github.com/thephpleague/flysystem-eventable-filesystem) version of Flysystem is available, and if so, it switches to it, letting you listen in on Flysystem [events](http://event.thephpleague.com/) and affect them accordingly.

> Note: This package only recognizes adapters NOT officially supported by [The PHP League](https://github.com/thephpleague?query=flysystem) - but it does depend on [danhunsaker/laravel-flysystem-service](https://github.com/danhunsaker/laravel-flysystem-service), which does provide the official adapters.

### Installation

The usual methods for using [Composer](https://getcomposer.org) apply here:

    composer require danhunsaker/laravel-flysystem-others

You do still have to register one service, but at least you don't have to write it:

```php
// In config/app.php

    'providers' => [
        // ...
        Danhunsaker\Laravel\Flysystem\FlysystemOtherServiceProvider::class,
        // ...
    ],
```

Be sure to register this one *instead of* `Danhunsaker\Laravel\Flysystem\FlysystemServiceProvider::class`, as this one extends it.

### Supported Adapters

The best place to check for which adapters are supported by this package is the Composer suggestions, but here's a quick (not-guaranteed-up-to-date) list as well:

- Aliyun OSS : [orzcc/aliyun-oss](https://packagist.org/packages/orzcc/aliyun-oss) or [shion/aliyun-oss](https://packagist.org/packages/shion/aliyun-oss)
- Baidu Bos : [zhuxiaoqiao/flysystem-baidu-bos](https://packagist.org/packages/zhuxiaoqiao/flysystem-baidu-bos)
- Cloudinary: [enl/flysystem-cloudinary](https://packagist.org/packages/enl/flysystem-cloudinary) or [t3chnik/flysystem-cloudinary-adapter](https://packagist.org/packages/t3chnik/flysystem-cloudinary-adapter)
- Eloquent: [rokde/flysystem-local-database-adapter](https://packagist.org/packages/rokde/flysystem-local-database-adapter)
- Fallback: [litipk/flysystem-fallback-adapter](https://packagist.org/packages/litipk/flysystem-fallback-adapter)
- Google Drive: [ignited/flysystem-google-drive](https://packagist.org/packages/ignited/flysystem-google-drive)
- GitHub: [potherca/flysystem-github](https://packagist.org/packages/potherca/flysystem-github)
- Google Cloud Storage: [superbalist/flysystem-google-storage](https://packagist.org/packages/superbalist/flysystem-google-storage)
- Mirror: A "meta-adapter" which combines the Fallback and Replicate adapters, if both are available.
- OneDrive: [ignited/flysystem-onedrive](https://packagist.org/packages/ignited/flysystem-onedrive)
- Qiniu: [eqingdan/flysystem-qiniu](https://packagist.org/packages/eqingdan/flysystem-qiniu) or [polev/flysystem-qiniu](https://packagist.org/packages/polev/flysystem-qiniu)
- Runabove: [engineor/flysystem-runabove](https://packagist.org/packages/engineor/flysystem-runabove)
- Sae: [coldwind/flysystem-sae](https://packagist.org/packages/coldwind/flysystem-sae)
- SMB/CIFS: [robgridley/flysystem-smb](https://packagist.org/packages/robgridley/flysystem-smb)
- Temp: [emgag/flysystem-tempdir](https://packagist.org/packages/emgag/flysystem-tempdir)

### Issues, Contributions, Etc

GitHub is the best place to interact about this project.

Enjoy!