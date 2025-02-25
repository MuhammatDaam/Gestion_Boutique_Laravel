<?php 
 
namespace App\Providers; 
 
use Illuminate\Auth\Events\Registered; 
use Illuminate\Auth\Listeners\SendEmailVerificationNotification; 
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider; 
use Illuminate\Support\Facades\Event; 
use App\Events\ClientCreated;
use App\Listeners\SendClientEmailListener;
use App\Listeners\StoreClientImage;
use App\Listeners\UploadPhotoToCloudinaryListener;
 
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
        'App\Events\ClientCreated' => [
            'App\Listeners\StoreClientImage',
        ],
        ClientCreated::class => [
            StoreClientImage::class,
            SendClientEmailListener::class,
            UploadPhotoToCloudinaryListener::class,
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
