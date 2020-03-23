<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModelUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:create {name} {dirname} {db?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据表明生成model';

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
        //
        $model = <<<EOF
<?php
namespace NAMESPACE;

use Illuminate\Database\Eloquent\Model;

class MODEL_NAME extends Model
{
    DB_CONN
    protected \$table='TABLE_NAME';
    protected \$primaryKey='ID';
    public \$timestamps = true;

    protected \$fillable=[
FIELDS    ];
}
EOF;
    
        $filePath = "./app/Models";
        if(!empty($this->argument('dirname'))){
            $filePath = "./app/Models/".ucfirst($this->argument("dirname"));
        }
        $nameSpace = str_replace("/","\\",ucfirst(substr($filePath,2)));
    
        $tableName  =  $this->argument('name');
        $temp =  explode('_',$tableName);
        $modelName = "";
        foreach ($temp as $name){
            $modelName .=ucfirst($name);
        }
        $model =  str_replace('MODEL_NAME',"Base".$modelName,$model);
        $model =  str_replace('TABLE_NAME',$tableName,$model);
        $model =  str_replace('NAMESPACE',$nameSpace,$model);
        try{
            if(!empty($this->argument('db'))){
                $conn  = DB::connection($this->argument('db'));
//                $cols=  $conn->select("SHOW COLUMNS FROM `".$tableName."`");
                $cols=  $conn->select(" SHOW FULL FIELDS FROM  `".$tableName."`");
            
                $model =  str_replace('DB_CONN',"protected \$connection='".$this->argument('db')."';",$model);
            }else{
//                $cols=  \DB::select("SHOW COLUMNS FROM `".$tableName."`");
                $cols=  DB::select(" SHOW FULL FIELDS FROM  `".$tableName."`");
                $model =  str_replace('DB_CONN',"",$model);
            }
            $fields = "";
            foreach ($cols as  $col){
            
                if($col->Key == 'PRI'){
                    $model =  str_replace('ID',$col->Field,$model);
                }else{
                    $fields .="        '".$col->Field."', // ".$col->Comment."\n";
                }
            }
            $model =  str_replace('FIELDS',$fields,$model);
//            $this->info(json_encode($this->arguments()));
        
            if(!File::isDirectory($filePath)){
                File::makeDirectory($filePath);
            }
            File::put($filePath."/Base$modelName.php",$model);
            $this->line($model);
            $this->info($filePath."/$modelName"."  Model Created!!");
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
    
        File::append($filePath."/Base$modelName.php","//Created at ".Carbon::now()->toDateTimeString());
    }
}
