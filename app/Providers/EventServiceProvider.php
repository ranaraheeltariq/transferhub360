<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Events\TransferCreated;
use App\Events\TransferUpdated;
use App\Events\AssigneVehicle;
use App\Events\CancelAssignedVehicle;
use App\Events\TransferDelete;
use App\Listeners\UetdsSeferEkle;
use App\Listeners\UetdsSeferGrupEkle;
use App\Listeners\UetdsPersonelEkle;
use App\Listeners\UetdsSeferGuncelle;
use App\Listeners\UetdsPersonelIptal;
use App\Listeners\UetdsSeferIptal;
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
        TransferCreated::class => [
            UetdsSeferEkle::class,
            UetdsSeferGrupEkle::class,
            UetdsPersonelEkle::class,
        ],
        TransferUpdated::class => [
            UetdsSeferGuncelle::class,
            UetdsPersonelIptal::class,
            UetdsSeferEkle::class,
            UetdsSeferGrupEkle::class,
            UetdsPersonelEkle::class,
        ],
        AssigneVehicle::class => [
            UetdsSeferGuncelle::class,
            UetdsPersonelIptal::class,
            UetdsSeferEkle::class,
            UetdsSeferGrupEkle::class,
            UetdsPersonelEkle::class,
        ],
        CancelAssignedVehicle::class => [
            UetdsPersonelIptal::class,
        ],
        TransferDelete::class => [
            UetdsSeferIptal::class,
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
