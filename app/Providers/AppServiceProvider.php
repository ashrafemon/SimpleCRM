<?php
namespace App\Providers;

use App\Models\Application;
use App\Models\Lead;
use App\Policies\ApplicationPolicy;
use App\Policies\LeadPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Gate::policy(Lead::class, LeadPolicy::class);
        Gate::policy(Application::class, ApplicationPolicy::class);
    }
}
