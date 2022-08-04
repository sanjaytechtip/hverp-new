<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;

use DB;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Log::info("Testing with cron job.");
        $arr = array("category"=>"dfdf","question"=>"asdfsf","answer"=>"sdfsdfsdf");
        DB::table('tbl_faq')->insertGetId($arr);
    }
}
