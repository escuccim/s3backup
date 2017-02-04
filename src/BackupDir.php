<?php

namespace Escuccim\S3Backup;

use Illuminate\Console\Command;
use Aws\Laravel\AwsFacade as AWS;

class BackupDir extends Command
{
    /**
     * The name and signature of the console command.
     * file = name of file to upload
     * dest = path/key to upload file to
     * path = path to file to upload, relative to public directory
     *
     * @var string
     */
    protected $signature = 'backup:dir {dir} {--dest=} {--ext=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uploads directory to Amazon S3';

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
    	$dir = $this->argument('dir');
    	$destination = $this->option('dest');
    	$extension = $this->option('ext');

    	$s3 = AWS::createClient('s3');

    	// get the name of the dir from the path
        $pathArray = explode('/', $dir);
        $dirName = end($pathArray);

        // get the files in the dir
        $path = base_path() . '/' . $dir;
        $files = $this->getDirectoryContents($path);

        $counter = 0;
        // loop through and upload them
        foreach($files as $file){
            $uploadFile = true;
            // if an extension was specified check if the file matches it
            if($extension) {
                $uploadFile = false;
                // filter out files without extensions
                $array = explode('/', $file);
                $fileName = end($array);

                if(strpos($fileName, '.')){
                    $fileExtension = pathinfo($file)['extension'];
                    if ($extension == $fileExtension)
                        $uploadFile = true;
                }
            }

            if($uploadFile) {
                $counter++;
                // strip out the base path
                $filePath = str_replace($path, '', $file);

                $destinationKey = $destination . $filePath;
                $s3->putObject(array(
                    'Bucket'     => env('AWS_BUCKET'),
                    'Key'        => $destinationKey,
                    'SourceFile' => $path . '/' . $filePath,
                ));
            }
        }
        echo $counter . " files uploaded.\n";
    }

    private function getDirectoryContents($path){
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        $files = array();
        foreach ($rii as $file) {
            if (!$file->isDir())
                $files[] = $file->getPathname();
        }

        return $files;

    }
}
