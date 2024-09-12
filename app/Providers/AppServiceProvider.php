<?php 
 
namespace App\Providers; 
 
use Illuminate\Support\ServiceProvider; 
use App\Repository\ArticleRepository;
use App\Services\ArticleService;
use App\Repository\ArticleRepositoryImpl;
use App\Repository\ClientRepositoryImpl;
use App\Services\ArticleServiceImpl;
use App\Services\ClientServiceImpl;
use App\Services\UploadServiceImpl;
use App\Services\PdfService;
use App\Services\PdfServiceImpl;
use App\Services\DetteServiceImpl;
use App\Repository\DetteRepositoryImpl;
use App\Repository\PaiementRepository;
use App\Repository\PaiementRepositoryImpl;
use App\Services\PaiementServiceImpl;
use App\Services\PaiementService;
use App\Services\MessengerTwillioService;
use App\Services\MessengerTwillioServiceImpl;

class AppServiceProvider extends ServiceProvider 
{ 
    /** 
     * Register any application services. 
     */ 
    public function register(): void 
    { 
        $this->app->bind(ArticleRepository::class, ArticleRepositoryImpl::class);
        $this->app->bind(ArticleService::class, ArticleServiceImpl::class);
        $this->app->bind(PaiementService::class, PaiementServiceImpl::class);
        $this->app->bind(PaiementRepository::class, PaiementRepositoryImpl::class);
        $this->app->singleton('ClientRepository', function ($app) {
            return new ClientRepositoryImpl();
        });

        $this->app->singleton('ClientService', function ($app) {
            return new ClientServiceImpl();
        });
        
        $this->app->singleton('uploadservice', function ($app) {
            return new UploadServiceImpl();
        });
        $this->app->bind(PdfService::class, PdfServiceImpl::class);

        $this->app->singleton('dette_service', function ($app) {
            return new DetteServiceImpl();
        });
        $this->app->singleton('dette_repository', function ($app) {
            return new DetteRepositoryImpl();
        });
        $this->app->bind(MessengerTwillioService::class, MessengerTwillioServiceImpl::class);
        
    } 
 

    /** 
     * Bootstrap any application services. 
     */ 
    public function boot(): void 
    { 
        // 
    } 
} 
