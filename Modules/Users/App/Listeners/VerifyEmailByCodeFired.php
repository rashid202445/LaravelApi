<?php

namespace Modules\Users\App\Listeners;

use Modules\Users\App\Events\VerifyEmailByCode;

use Modules\Users\App\Emails\SendActiveCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class VerifyEmailByCodeFired
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
    public function handle(VerifyEmailByCode $event): void
    {
        //
        Mail::to($event->user->email)->send(new SendActiveCode(__('main.active_account', [
            'type' => __('main.email')
        ]), __('main.email_code_msg', [
                'name' => $event->user->name,
                'code' => $event->user->email_code
            ])));
    }
}
