<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PublishPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    public function handle()
    {
        Log::info('Post published:', $this->requestData);

        // Optionally, return a value indicating the result of the operation
        return ['status' => 'success', 'message' => 'Post published'];

        // $scheduledTime = $this->requestData['scheduledTime'];
        // $postData = $this->requestData['postData'];
    }
    
}
