<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Wallets\Databases\Entities\WalletEntity;
use App\Observers\WalletUserObserver;
use App\Models\Wallets\Databases\Entities\WalletUserEntity;
use App\Models\Wallets\Databases\Entities\WalletDetailEntity;
use App\Observers\WalletDetailObserver;
use App\Observers\WalletObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //observe
        WalletUserEntity::observe(WalletUserObserver::class);
        WalletDetailEntity::observe(WalletDetailObserver::class);
        WalletEntity::observe(WalletObserver::class);
    }
}
