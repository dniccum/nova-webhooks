<?php

namespace Dniccum\NovaWebhooks\Tests;

use Dniccum\NovaWebhooks\Database\Migrations\CreatePageViewsTable;
use Dniccum\NovaWebhooks\Database\Migrations\CreateWebhooksTable;
use Dniccum\NovaWebhooks\Models\Webhook;
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
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->artisan('migrate', ['--database' => 'testing']);

        include_once __DIR__ . '/../database/migrations/create_webhooks_table.php.stub';
        include_once __DIR__ . '/database/migrations/create_page_views_table.php.stub';
        (new CreatePageViewsTable())->up();
        (new CreateWebhooksTable())->up();
    }

    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Spatie\WebhookServer\WebhookServerServiceProvider::class,
        ];
    }
}
