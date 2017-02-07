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
### To Dump and Upload Database
```bash
php artisan backup:db {--path=} {--db=} {--user=} {--dest=} {--keep} {--p} {--pass=}
```
This will dump the database specified in your .env file to your /database/ directory, upload the dump to S3, and then delete it locally.

Options:
- path: by default the db will be dumped to your database directory, if you want to dump to a subdirectory specify this in path.
- db: the name of the DB to dump, if you omit this it will use the DB from the .env file
- user: the name of the user to use to dump, if you omit this it will use the username from the .env file
- dest: the key to upload the file to in s3
- keep: use this flag if you want to keep the dump file locally, otherwise it will be deleted after upload
- p: use this flag if you want to use the password in your .env file when generating the dump. If you do not use this flag no password will be specified.
- pass: if you wish to use a password other than the one in the .env specify it here.

All of these parameters are optional and if not specified defaults will be taken from the .env file.

Note that --p is used to specify if you want to use a password at all, if you do not have a password for the local account or have credentials stored in a .my.cnf file you can omit this. 

--pass= is used to specify a password, if you wish to use it you MUST use the --p flag as well. If you use --p with no password specified the password will be taken from the .env file.

### For Single Files
``` bash 
php artisan backup:file [file] [--dest=location to upload file to]
```

{file} is the path to the file to upload relative to your project root. {dest} is the location to upload the file to. The file will keep the same name. 

Do not use trailing or leading "/"s on either the file or the destination.

### For Directories
```bash
php artisan backup:dir [path] [--dest=location to upload file to] [--ext=file extension]
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
