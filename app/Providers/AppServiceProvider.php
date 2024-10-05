<?php

namespace App\Providers;

use App\Models\{
    Category,
    Company
};
use App\Observers\{
    CategoryObserver,
    CompanyObserve
};
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
        Category::observe(CategoryObserver::class);
        Company::observe(CompanyObserve::class);
    }
}
