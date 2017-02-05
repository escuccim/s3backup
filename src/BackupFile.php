<?php

namespace Escuccim\S3Backup;

use Illuminate\Console\Command;
use Aws\Laravel\AwsFacade as AWS;

class BackupFile extends Command
{
    /**
     * The name and signature of the console command.
     * file = name of file to upload
     * dest = path/key to upload file to
     * path = path to file to upload, relative to public directory
     *
     * @var string
     */
    protected $signature = 'backup:file {file} {--dest=}';

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

    	// get file name
        $path = explode('/', $file);
        $fileName = end($path);

    	$s3 = AWS::createClient('s3');
    	
    	$s3->putObject(array(
    			'Bucket'     => env('AWS_BUCKET'),
    			'Key'        => $destination . '/' . $fileName,
    			'SourceFile' => base_path() . '/' . $file,
    	));
    }
}
