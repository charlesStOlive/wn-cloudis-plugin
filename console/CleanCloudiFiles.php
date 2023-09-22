<?php namespace Waka\Cloudis\Console;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Waka\Cloudis\Models\CloudiFile;
use \Waka\Cloudis\Models\Settings as CloudisSettings;

/**
 * @author Boris Koumondji <brexis@yahoo.fr>
 */
class CleanCloudiFiles extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'waka:cleanCloudiFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permet de nettoyer les fichiers de cloudi non utilisé';

    protected $executeClean = false;

    protected $cleanFile = false;


    /**
     * A mapping of stub to generated file.
     *
     * @var array
     */

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option('exec')) {
            $this->executeClean = true;
        }
        $yesterday = \Carbon\Carbon::now()->subDay();
        $cloudiFile = CloudiFile::whereNull('attachment_id')->whereNull('attachment_type')->where('updated_at', '<', $yesterday);
        $count = $cloudiFile->count();
        $this->info('Delete file orphans : '.$count);
        if($count ) {
            foreach($cloudiFile->get() as $file) {
                $this->info('Netoyage file  : '.$file->disk_name);
                if($this->executeClean) {
                    // $file->deleteCloudi();
                }
            }
        }
        $imageToDelete = $this->selectDistinctFile('image');
        foreach($imageToDelete as $image) {
                $this->info('Suppr sur cloudi  : '.$image);
                if($this->executeClean) {
                    // $file->deleteCloudi();
                }
            }
    }

    protected function selectDistinctFile($type)
    {
        $cloudiPath = CloudisSettings::get('cloudinary_path');
        $distantCloudiImage = CloudiFile::getAllFiles($type);
        $localCloudiImages = CloudiFile::where('content_type', 'LIKE', '%'.$type.'%')->get()->pluck('disk_name')->toArray();
        $renamedImages = [];
        foreach($localCloudiImages as $image) {
            $renamedImages[] = $cloudiPath.'/'.$image;
        }
        //trace_log($distantCloudiImage);
        //trace_log($renamedImages);
        return  array_diff($distantCloudiImage, $renamedImages);
        // trace_log(CloudiFile::getAllFiles('video'));


        
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['exec', null, InputOption::VALUE_NONE, 'Executer le clean sinon affiche en log uniquement'],
        ];
    }
}
