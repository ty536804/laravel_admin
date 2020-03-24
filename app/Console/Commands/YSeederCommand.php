<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class YSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeder:create {name} {db?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据表名 导出Seed 文件 导出后需要执行 composer dump-autoload';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    protected  $connName = "magic_math";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        if($this->hasArgument('db')){
            $this->connName = $this->argument('db');
        }
        
        $model = $this->getTemplate();
        $modelName="";
        $filePath = "./database/seeds/";
        if($this->hasArgument('modelname')){
            $modelName = ucfirst($this->argument('modelname'))."\\";
            $filePath = "./database/seeds/".ucfirst($this->argument('modelname'))."/";
        }
        $tableName  =  $this->argument('name');
        $list  =  $this->getData($tableName);
        
        $className = $this->getClassName($tableName);
        
        $model =  str_replace('MODEL_NAME',$modelName,$model);
        $model =  str_replace('CLASS_NAME',$className,$model);
        $model =  str_replace('INSERTS',$this->getSeedStr($list),$model);
        try{
            if(!File::isDirectory($filePath)){
                File::makeDirectory($filePath);
            }
            File::put($filePath."/".$className."Seeder.php",$model);
            $this->line($model);
            $this->info($filePath."/".$className."Seeder.php Seeder Created!!");
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        File::append($filePath."/".$className."Seeder.php","//Created at ".Carbon::now()->toDateTimeString());
        
        //重新执行
        $composer =  new Composer(new Filesystem());
        $composer->dumpAutoloads();
    }
    
    
    //===
    //===
    
    protected function getTemplate(){
        $model = <<<EOF
<?php
use Illuminate\Database\Seeder;
class CLASS_NAMESeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MODEL_NAMEBase\BaseCLASS_NAME::truncate();
        \App\Models\MODEL_NAMEBase\BaseCLASS_NAME::insert(
         INSERTS
        );
    }
}
EOF;
        return $model;
    }
    
    protected function getClassName($table){
        $tableString = '';
        $tableName = explode('_', $table);
        foreach ($tableName as $v) {
            $tableString .= ucfirst($v);
        }
        return ucfirst($tableString);
    }
    /**
     * Get the Data
     * @param  string $table
     * @return Array
     */
    protected function getData($table, $max=0, $exclude = null, $orderBy = null, $direction = 'ASC')
    {
        $result = DB::connection($this->connName)->table($table);
        if (!empty($exclude)) {
            $allColumns = DB::connection($this->connName)->getSchemaBuilder()->getColumnListing($table);
            $result = $result->select(array_diff($allColumns, $exclude));
        }
        if($orderBy) {
            $result = $result->orderBy($orderBy, $direction);
        }
        if ($max) {
            $result = $result->limit($max);
        }
        return $result->get();
    }
    protected function getSeedStr($list){
        $str = json_encode($list,JSON_UNESCAPED_UNICODE);
        $str= str_replace('{','[',$str);
        $str= str_replace('}',']',$str);
        $str= str_replace('],[','],'.PHP_EOL.'[',$str);
        $str= str_replace('":','"=>',$str);
        return $str;
    }
}
