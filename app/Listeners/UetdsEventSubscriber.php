<?php
 
namespace App\Listeners;
 
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class UetdsEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function uetdsPersonelEkle(string $event): void {}
 
    /**
     * Handle user logout events.
     */
    public function handleUserLogout(string $event): void {}
 
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [UserEventSubscriber::class, 'uetdsPersonelEkle']
        );
 
        $events->listen(
            Logout::class,
            [UserEventSubscriber::class, 'handleUserLogout']
        );
    }
}