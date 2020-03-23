<?php

namespace App\Providers;

use App\Helpers\Logs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(190);
        Carbon::setLocale('zh');
        require app_path().'/Services/validations.php';
        
        $sql_debug = config('database.sql_debug');
        if ($sql_debug) {
            DB::listen(function ($sql) {
                if(!Str::startsWith($sql->sql,'explain')){
                    foreach ($sql->bindings as $i => $binding) {
                        if ($binding instanceof \DateTime) {
                            $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                        } else {
                            if (is_string($binding)) {
                                $sql->bindings[$i] = "'$binding'";
                            }
                        }
                    }
                    $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
                    $query = vsprintf($query, $sql->bindings);
                    $query_key = md5($query);
                    $execute_id = md5($query.time().rand(10000,99999));
                    $sql_key = md5($sql->sql);
                    Log::debug('运行SQL：' . $query);
                    Log::debug('运行耗时：' . $sql->time . ' ms');
                    Cache::put($execute_id, $sql->time . "ms", 1);
                    if(Str::startsWith($query,"select")){
                        try{
                            $result= DB::select("explain ".$query);
                            foreach ($result as $v){
                                $v->sql_key = $sql_key;
                                $v->query_key = $query_key;
                                $v->query_sql = $query;
                                $v->query_time= Cache::get($execute_id, '0');
                                $v->execute_id = $execute_id;
                                $v->application = config('app.name');
                                $v->msg = "Explain execute successful";
//                                Log::debug(json_encode($v),['type'=>'SqlExpain'],'sql_explain');
                            }
                        }catch (\Exception $e){
                            $v = new \stdClass();
                            $v->sql_key = $sql_key;
                            $v->query_key = $query_key;
                            $v->query_sql = $query;
                            $v->query_time= Cache::get($execute_id, '0');
                            $v->executeid = $execute_id;
                            $v->application = config('app.name');
                            $v->msg = "Explain execute error : ".$e->getMessage();
//                            Log::debug(json_encode($v),['type'=>'SqlExpain'],'sql_explain');
                        }
                    }
                }
            });
        }
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
