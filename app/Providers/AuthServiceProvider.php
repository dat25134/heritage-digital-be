<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Allow admin to bypass all checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Define fine-grained gates mapped to spatie permissions
        $modules = [
            'images' => ['create', 'read', 'update', 'delete'],
            'image-intros' => ['create', 'read', 'update', 'delete', 'publish'],
            'topics' => ['create', 'read', 'update', 'delete', 'order'],
            'videos' => ['create', 'read', 'update', 'delete', 'upload', 'publish'],
            'posts' => ['create', 'read', 'update', 'delete', 'publish'],
            'papers' => ['create', 'read', 'update', 'delete', 'publish'],
            'backups' => ['read', 'create', 'restore', 'delete'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $ability = $module . '.' . $action;
                Gate::define($ability, function ($user) use ($ability) {
                    return $user->can($ability);
                });
            }
        }
    }
}
