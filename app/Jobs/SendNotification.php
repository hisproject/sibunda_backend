<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Plokko\Firebase\FCM\Message;
use Plokko\Firebase\FCM\Request;
use Plokko\Firebase\FCM\Targets\Target;
use Plokko\Firebase\FCM\Targets\Token;
use Plokko\Firebase\ServiceAccount;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $notification;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        //
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try {
            $sa = Storage::disk('local')->get('sibunda-718c1-firebase-adminsdk-8gr8o-419f2c128d.json');
            $sa = new ServiceAccount($sa);
            $target = new Token($this->notification->fcm_token);
            $client = new Client();
            $validate_only = false;
            $request = new Request($sa, $validate_only, $client);

            $message = new Message();
            $message->notification
                ->setTitle($this->notification->title)
                ->setBody($this->notification->body);

            $message->data->fill([
                'title' => $this->notification->title,
                'body' => $this->notification->body,
                'img_url' => $this->notification->order_id,
            ]);

            $message->setTarget($target);
            $message->send($request);

            Log::info('notification ' . $this->notification->title. ' : ' .
                $this->notification->body .' : sent to client {' . $this->notification->fcm_token . '}');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
