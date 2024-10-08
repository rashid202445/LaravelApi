<?php

namespace Modules\Users\App\Listeners;

use Modules\Users\App\Events\VerifyMobileByCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;

class VerifyMobileByCodeFired
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerifyMobileByCode $event): void
    {
        // dd($event->user);
        //
        print_r("Reminder Daemon Started \n");
       // while (true) {
            $account_sid = env('TWILIO_SID');
            $account_token = env("TWILIO_TOKEN");
            $sending_number = env("TWILIO_NUMBER");
            $twilio_client = new Client($account_sid, $account_token);
            // $now = Carbon::now('Africa/Lagos')->toDateTimeString();
            // $reminders = Reminder::where([['timezoneoffset', '=', $now], ['status', 'pending']])->get();
            // foreach ($reminders as $reminder) {
                $twilio_client->messages->create('+967'.$event->user->mobile,
                    array("from" => $sending_number, "body" => __('main.email_code_msg',[
                        'name'=>$event->user->name,
                        'code'=>$event->user->mobile_code
                    ])));
              //  $reminder->status = 'sent';
               // $reminder->save();
            // }
          //  \sleep(1);
      //  }
    }
}
