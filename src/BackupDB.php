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
    protected $signature = 'backup:db {--path=} {--dest=} {--keep} {--usepass}';

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
        $path = $this->option('path');
        $keepFile = $this->option('keep');
        $usePass = $this->option('usepass');

        if($destination)
            $destination .= '/';

    	// get the db name
        $db = env('DB_DATABASE');

    	// generate a backup name
        $name = $db . '_' . date('Y-m-d') . '.sql.gz';
        $backupLocation = database_path() . '/' . $path . '/' . $name;

    	// dump the database
        $command = 'mysqldump -u ' . env('DB_USERNAME') ;
        if($usePass){
            $command .= ' -p' .  env('DB_PASSWORD');
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
