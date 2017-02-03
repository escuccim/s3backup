# Laravel package to create console command to backup files to Amazon S3

## Installation
Requires aws/aws-sdk-php-laravel

Put this file in /app/Console/Commands

Register it in /app/Console/Kernel.php:
```php
protected $commands = [
  Commands\BackupFile::class,
];
```
Execute as php artisan backup:db [filename] --path=[path to file] --dest=[location to upload to] 

Note do not use leading or trailings /s in the path or the destination.

It will upload the file to Amazon S3 in the specified location.

You must have the S3 configuration in your .env file:
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_REGION=
AWS_BUCKET=

I use this to dump and backup my DB. I have a cron job scheduled which dumps the DB and then executes this command to upload it.
