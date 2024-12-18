<?php

namespace Dniccum\NovaWebhooks;

use Laravel\Nova\Nova;
use Illuminate\Support\Carbon;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Dniccum\NovaWebhooks\Library\ModelUtility;
use Dniccum\NovaWebhooks\Library\WebhookUtility;
use Dniccum\NovaWebhooks\Http\Middleware\Authorize;
use Coroowicaksono\ChartJsIntegration\CardServiceProvider;
use Dniccum\NovaWebhooks\Providers\ToolEventServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-webhooks');
        $this->migrations()
            ->novaResources();

        $this->app->booted(function () {
            $this->routes();
        });
        if (!\App::runningUnitTests()) {
            Nova::serving(function (ServingNova $event) {
                //
            });
        }
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/nova-webhooks')
                ->group(__DIR__.'/../routes/api.php');
    }

    protected function migrations()
    {
        if ($this->app->runningInConsole()) {
            $now = Carbon::now();
            if (! class_exists('CreateWebhooksTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_webhooks_table.php.stub' => database_path('migrations/' . $now->format('Y_m_d_His') . '_create_webhooks_table.php')
                ], 'nova-webhooks');
            }
            if (! class_exists('CreateWebhookLogsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_webhook_logs_table.php.stub' => database_path('migrations/' . $now->addSecond()->format('Y_m_d_His') . '_create_webhook_logs_table.php')
                ], 'nova-webhooks');
            }
        }

        return $this;
    }

    protected function novaResources()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nova-webhooks');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/nova-webhooks'),
        ], 'nova-webhooks-translations');

        return $this;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAddonConfig();

        $this->app->bind('webhooks', function() {
            return new WebhookUtility;
        });
        $this->app->bind('webhook-models', function() {
            return new ModelUtility;
        });
        $this->app->register(ToolEventServiceProvider::class);
        $this->app->resolveProvider(CardServiceProvider::class);
    }

    protected function registerAddonConfig() : ToolServiceProvider
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nova-webhooks.php', 'nova-webhooks');
        $this->mergeConfigFrom(__DIR__.'/../config/webhook-server.php', 'webhook-server');

        $this->publishes([
            __DIR__.'/../config/nova-webhooks.php' => config_path('nova-webhooks.php'),
            __DIR__.'/../config/webhook-server.php' => config_path('webhook-server.php'),
        ], 'nova-webhooks-config');

        return $this;
    }
}
