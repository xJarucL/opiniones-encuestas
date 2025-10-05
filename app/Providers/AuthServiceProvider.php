<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Comentario::class => \App\Policies\ComentarioPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Si 1 = admin
        Gate::define('moderate-comments', fn($user) => (int)$user->fk_tipo_user === 1);
    }
}
