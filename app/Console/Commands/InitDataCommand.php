<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:init {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $tableName  =  $this->argument('name');
        $func = "init".ucfirst($tableName)."Database";
        call_user_func("App\Console\Commands\InitDataCommand::".$func);
        $this->info( $func ." Compelete!!");
    }
    
    public function initAdminDatabase() {
        $this->call("db:seed",['--class' => \SysAdminPowerSeeder::class]);
        $this->call("db:seed",['--class' => \SysAdminUserSeeder::class]);
        $this->call("db:seed",['--class' => \SysAreacodeSeeder::class]);
    }
}
