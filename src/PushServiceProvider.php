<?php

namespace Raim\FluxNotify\Push;

use Raim\FluxNotify\Push\Contracts\PushDriverInterface;
use Raim\FluxNotify\Push\Drivers\FirebasePushDriver;
use Illuminate\Support\ServiceProvider;

class PushServiceProvider extends ServiceProvider
{
    public $bindings = [
//        PushDriverInterface::class => ExpoPushDriver::class,
        PushDriverInterface::class => FirebasePushDriver::class
    ];

    public function register()
    {
        $this->app->bind('fluxPushService', PushService::class);
    }
}
