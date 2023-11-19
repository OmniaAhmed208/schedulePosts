<?php

namespace App\Console\Commands;

use App\Models\Api;
use Illuminate\Console\Command;

class FacebookApiTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:facebook-api-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the Facebook API task.';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // $interval = Api::all()->last();
        // if ($interval) {
        //     $interval->call('facebook-api-task')->delay(now()->addMinutes($interval->update_interval));
        // }
    }
}
