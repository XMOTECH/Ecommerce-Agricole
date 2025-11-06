<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Configuration spécifique pour les routes API
        $this->configureApiRoutes();

        // Configuration spécifique PostgreSQL
        $this->configurePostgreSQL();
        
        // Configuration des services
        $this->configureServices();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
        
        RateLimiter::for('orders', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        
        RateLimiter::for('payments', function (Request $request) {
            return Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Configuration spécifique PostgreSQL
     */
    private function configurePostgreSQL(): void
    {
        // Configuration des transactions pour PostgreSQL
        DB::beforeExecuting(function ($query) {
            if (str_contains($query->sql, 'SELECT') && !str_contains($query->sql, 'FOR UPDATE')) {
                // Optimisation pour les requêtes de lecture
                if (str_contains($query->sql, 'products') && str_contains($query->sql, 'is_active')) {
                    $query->sql = str_replace('is_active = 1', 'is_active = true', $query->sql);
                }
            }
        });
    }

    /**
     * Configuration spécifique pour les routes API
     */
    private function configureApiRoutes(): void
    {
        // S'assurer que les routes API sont correctement configurées
        // Les routes API utilisent le middleware 'api' qui exclut CSRF
        // et retourne des réponses JSON
    }

    /**
     * Configuration des services
     */
    private function configureServices(): void
    {
        // S'assurer que les rôles existent
        if (app()->environment('local', 'testing')) {
            try {
                \App\Services\RoleService::ensureRolesExist();
            } catch (\Exception $e) {
                // Log mais ne pas fail l'application
                \Illuminate\Support\Facades\Log::warning('Erreur lors de l\'initialisation des rôles', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}