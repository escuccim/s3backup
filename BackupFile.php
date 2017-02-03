<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Aws\Laravel\AwsFacade as AWS;

class BackupFile extends Command
{
    /**
     * The name and signature of the console command.
     * file = path to file to upload, relative to public directory
     * dest = path to upload file to
     *
     * @var string
     */
    protected $signature = 'backup:db {file} {--path=/} {--dest=/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uploads files to Amazon S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$file = $this->argument('file');
    	$destination = $this->option('dest');
    	$path = $this->option('path');
    	
    	$s3 = AWS::createClient('s3');
    	
    	$s3->putObject(array(
    			'Bucket'     => env('AWS_BUCKET'),
    			'Key'        => $destination . '/' . $file,
    			'SourceFile' => public_path() . '/' . $path . '/' . $file,
    	));
    }
}
