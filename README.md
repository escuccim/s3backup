# S3Backup

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

This is a Laravel package that creates a console command to backup a file to Amazon S3.

## Install

Via Composer -

``` bash
$ composer require escuccim/s3backup
```
Register the class in config/app.php 'providers' array:
```
Escuccim\S3Backup\S3BackupServiceProvider::class,
```
You must specify the credentials to your Amazon S3 bucket in your .env file as follows:
```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_REGION=
AWS_BUCKET=
```

## Usage
### For Single Files
``` bash 
artisan backup:file [file] [--dest=location to upload file to]
```

{file} is the path to the file to upload relative to your project root. {dest} is the location to upload the file to. The file will keep the same name. 

Do not use trailing or leading "/"s on either the file or the destination.

### For Directories
```bash
artisan backup:dir [path] [--dest=location to upload file to] [--ext=file extension]
```
Where path is the path to the directory relative to the base path and dest is the base key to upload the files to. The final destination of the files will replace the path parameter with the dest parameter.

If you specify a file extension only files matching that will be uploaded. 

## Credits

- [Eric Scuccimarra][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/escuccim/s3backup.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/escuccim/s3backup/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/escuccim/s3backup.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/escuccim/s3backup.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/escuccim/s3backup.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/escuccim/s3backup
[link-travis]: https://travis-ci.org/escuccim/s3backup
[link-scrutinizer]: https://scrutinizer-ci.com/g/escuccim/s3backup/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/escuccim/s3backup
[link-downloads]: https://packagist.org/packages/escuccim/s3backup
[link-author]: http://ericscuccimarra.com
