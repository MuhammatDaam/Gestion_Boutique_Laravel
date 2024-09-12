<?php 
 
namespace App\Console; 
 
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel; 
use App\Jobs\MessengerTwillioJob;
use App\Services\MessengerTwillioService;
 
class Kernel extends ConsoleKernel 
{ 
    /** 
     * Define the application's command schedule. 
     */ 
    protected function schedule(Schedule $schedule): void 
    { 
        // $schedule->command('inspire')->hourly(); 
 
        //$schedule->job(new MessengerTwillioJob(app(MessengerTwillioService::class)))->everyMinute();
        $schedule->job(new MessengerTwillioJob())->everyFiveSeconds();
    } 
 
    /** 
     * Register the commands for the application. 
     */ 
    protected function commands(): void 
    { 
        $this->load(__DIR__.'/Commands'); 
 
        require base_path('routes/console.php'); 
    } 
} 
