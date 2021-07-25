<?php

namespace App\Jobs;

use App\Http\Controllers\Mobile\GlobalDataHelper;
use App\Models\KiaIdentitasAnak;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\ServiceStatementIbuHamil;
use App\Models\User;
use App\Utils\Constants;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyLatePregnancies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GlobalDataHelper;

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $users = User::where('user_group_id', Constants::USER_GROUP_BUNDA)->get();
        $template = NotificationTemplate::find(3);

        foreach($users as $user) {
            $anak = KiaIdentitasAnak::where('kia_ibu_id', $user->kia_ibu)
                                        ->where('is_janin', true)->get();
            foreach($anak as $a) {
                $age = $this->getPregnancyAgeInWeek($a->hpl);
                if($age > 40) {
                    $notif = Notification::create([
                        'is_message' => false,
                        'title' => $template->title,
                        'desc' => $template->desc,
                        'datetime' => Carbon::now(),
                        'img_url' => $template->img_url,
                        'url' => 'https://www.google.com/',
                        'user_id' => $user->id,
                        'template_id' => 3
                    ]);
                    $push_notif = new \stdClass();
                    $push_notif->title = $notif->title;
                    $push_notif->body = $notif->desc;
                    $push_notif->img_url = $notif->img_url;
                    $push_notif->fcm_token = $user->fcm_token;

                    SendNotification::dispatch($push_notif);
                }
            }
        }
    }
}
