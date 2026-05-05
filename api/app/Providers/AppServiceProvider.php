<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\CV\Models\Cv;
use App\Domain\CV\Policies\CvPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /** @var array<class-string<Model>, class-string> */
    private array $policies = [
        Cv::class => CvPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        $this->configureRateLimiting();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn (Request $request): Limit => Limit::perMinute(60)
            ->by($request->user()?->id ?: $request->ip()));

        // Stricter throttles on auth endpoints — the LoginUserAction also
        // tracks email+IP attempts, but a per-IP limit at the edge protects
        // the password hashing pipeline from raw bursts.
        RateLimiter::for('login', fn (Request $request): Limit => Limit::perMinute(5)
            ->by((string) $request->ip()));

        RateLimiter::for('register', fn (Request $request): Limit => Limit::perMinute(3)
            ->by((string) $request->ip()));
    }
}
