<?php

namespace App\Providers;
use Laravel\Passport\Passport;
use App\Models\Article;
use App\Models\Client;
use App\Models\User;
use App\Policies\ArticlePolicy;
use App\Policies\ClientPolicy;
use App\Policies\UserPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Client::class => ClientPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}
