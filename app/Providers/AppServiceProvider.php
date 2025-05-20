<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Policies\AdministrativePolicy;

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
        Gate::policy(User::class, AdministrativePolicy::class);

        Gate::define('use-cart', function (?User $user) {
            return $user === null || $user->type == 'A' || $user->type == 'S';
        });

        Gate::define('confirm-cart', function (User $user) {
            return $user->type == 'A' || $user->type == 'S';
        });

        Gate::define('admin', function (User $user) {
            // Only "administrator" users can "admin"
            return $user->admin;
        });

        try {
            // View::share adds data (variables) that are shared through all views
            View::share('courses', Course::all());
        } catch (\Exception $e) {
            // No need to do anything – this just ensures that no exception is
            // thrown if "courses" table does not exist
        }
    }
}
