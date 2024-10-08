<?php

namespace Modules\Users\App\Providers;
// namespace Modules\Users\App\Events;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Users\App\Events\VerifyEmailByCode;
use Modules\Users\App\Events\VerifyMobileByCode;
use Modules\Users\App\Listeners\VerifyEmailByCodeFired;
use Modules\Users\App\Listeners\VerifyMobileByCodeFired;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
         VerifyEmailByCode::class=>[
            VerifyEmailByCodeFired::class,
        ],
        VerifyMobileByCode::class=>[
            VerifyMobileByCodeFired::class,
        ],
    ];
}
