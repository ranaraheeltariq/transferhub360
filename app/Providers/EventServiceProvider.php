<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Events\CompanyCreated;
use App\Events\TransferCreated;
use App\Events\TransferSeferGrupCreated;
use App\Events\TransferUpdated;
use App\Events\TransferDriverAssigned;
use App\Events\TransferDriverUnassigned;
use App\Events\TransferDelete;
use App\Events\PassangerAttached;
use App\Listeners\UetdsSeferEkle;
use App\Listeners\UetdsSeferGrupEkle;
use App\Listeners\UetdsPersonelEkle;
use App\Listeners\UetdsSeferGuncelle;
use App\Listeners\UetdsPersonelIptal;
use App\Listeners\UetdsSeferIptal;
use App\Listeners\YolcuEkle;
use App\Listeners\CompanyRolesCreation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CompanyCreated::class => [
            CompanyRolesCreation::class,
        ],
        TransferCreated::class => [
            UetdsSeferEkle::class,
        ],
        TransferSeferGrupCreated::class => [
            UetdsSeferGrupEkle::class,
        ],
        TransferUpdated::class => [
            UetdsSeferGuncelle::class,
        ],
        TransferDriverAssigned::class => [
            UetdsPersonelEkle::class,
        ],
        TransferDriverUnassigned::class => [
            UetdsPersonelIptal::class,
        ],
        TransferDelete::class => [
            UetdsSeferIptal::class,
        ],
        PassangerAttached::class => [
            YolcuEkle::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
