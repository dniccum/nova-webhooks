<?php

namespace Dniccum\NovaWebhooks\Tests;

use Dniccum\NovaWebhooks\Database\Migrations\CreatePageLikesTable;
use Dniccum\NovaWebhooks\Database\Migrations\CreatePageViewsTable;
use CreateWebhookLogsTable;
use CreateWebhooksTable;
use Dniccum\NovaWebhooks\Models\Webhook;
use Dniccum\NovaWebhooks\Tests\Models\Api\PageLike;
use Dniccum\NovaWebhooks\Tests\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->makeFaker();

        Webhook::truncate();
        PageView::truncate();
        PageLike::truncate();
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->artisan('migrate', ['--database' => 'testing']);

        include_once __DIR__ . '/../database/migrations/create_webhooks_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_webhook_logs_table.php.stub';
        include_once __DIR__ . '/database/migrations/create_page_views_table.php.stub';
        include_once __DIR__ . '/database/migrations/create_page_likes_table.php.stub';
        (new CreatePageViewsTable())->up();
        (new CreatePageLikesTable())->up();
        (new CreateWebhooksTable())->up();
        (new CreateWebhookLogsTable())->up();
    }

    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Dniccum\NovaWebhooks\ToolServiceProvider::class,
            \Spatie\WebhookServer\WebhookServerServiceProvider::class,
        ];
    }
}
