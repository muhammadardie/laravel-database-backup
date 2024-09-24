<?php
 
namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
 
class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event): void {
        $userId = $event->user->id;
        User::find($userId)->update([
            'last_login' => date('Y-m-d H:i:s'),
            'is_logged' => true
        ]);
    }
 
    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void {
        $userId = $event->user->id;
        User::find($userId)->update([
            'is_logged' => false
        ]);
    }
 
    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
        ];
    }
}