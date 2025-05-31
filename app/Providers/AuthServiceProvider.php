<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
        \App\Models\PhongKho::class => \App\Policies\PhongKhoPolicy::class,
    ];


    public function register(): void
    {
        //
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->isAdmin(); // quyền cho Admin
        });
        Gate::define('isManager', function ($user) {
            return $user->isManager(); // quyền cho Manager
        });
        Gate::define('isLecturer', function ($user) {
            return $user->isLecturer(); // quyền cho Lecturer
        });
        Gate::define('isStandardUser', function ($user) {
            return $user->isStandardUser(); // quyền cho Standard User
        });
        Gate::define('hasRole_Admin_Manager', function ($user) {
            return in_array($user->maloaitk, [1, 2]);
        });
        Gate::define('hasRole_Lecturer_SU', function ($user) {
            return in_array($user->maloaitk, [3, 4]);
        });
        Gate::define('hasRole_A_M_L', function ($user) {
            return in_array($user->maloaitk, [1, 2, 4]);
        });
    }
}
