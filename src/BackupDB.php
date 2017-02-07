<?php

namespace Escuccim\S3Backup;

use Illuminate\Console\Command;
use Aws\Laravel\AwsFacade as AWS;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     * file = name of file to upload
     * dest = path/key to upload file to
     * path = path to file to upload, relative to public directory
     *
     * @var string
     */
    protected $signature = 'backup:db {--path=} {--db=} {--user=} {--dest=} {--keep} {--p} {--pass=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backsup database to Amazon S3';

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
    	$destination = $this->option('dest');
    	$db = $this->option('db');
    	$user = $this->option('user');
        $path = $this->option('path');
        $keepFile = $this->option('keep');
        $pass = $this->option('pass');
        $usePass = $this->option('p');

        // add a trailing / to the end of destination, if provided
        if($destination)
            $destination .= '/';
       
        // if no db specified default if
        if(!$db)
            $db = env('DB_DATABASE');

        // if no user specified default that
        if(!$user)
            $user = env('DB_USERNAME');

        // check if we should use a password
        if($usePass){
            // if yes and none is provided get it from the .env file
            if(is_null($pass)){
                $password = env('DB_PASSWORD');
            } else {
                $password = $pass;
            }
        }

    	// get the db name
        $db = env('DB_DATABASE');

    	// generate a backup name
        $name = $db . '_' . date('Y-m-d') . '.sql.gz';
        $backupLocation = database_path() . '/' . $path . '/' . $name;

        // dump the database
        $command = 'mysqldump -u ' . $user ;
        if($usePass){
            $command .= ' -p' .  $password;
        }
        $command .=   ' --databases ' . $db . '| gzip > ' . $backupLocation;
        exec($command);

        // upload the
        $s3 = AWS::createClient('s3');
        $s3->putObject(array(
    			'Bucket'     => env('AWS_BUCKET'),
    			'Key'        => $destination . $name,
    			'SourceFile' => $backupLocation,
    	));

        // if keep is not specified delete the file
        if(!$keepFile){
            for($i = 0; $i < 10; $i++){
                sleep(1);
                echo ".";
            }
            gc_collect_cycles();
            exec('rm -f ' . $backupLocation);
        }

    	echo "Database dumped and uploaded\n";
    }
}
