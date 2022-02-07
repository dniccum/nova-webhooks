<?php

namespace Dniccum\NovaWebhooks\Tests\Feature;

use Dniccum\NovaWebhooks\Models\Webhook;
use Dniccum\NovaWebhooks\Tests\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Dniccum\NovaWebhooks\Tests\TestCase;
use Spatie\WebhookServer\CallWebhookJob;

class ModelEventsTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * @test
     * @covers \Dniccum\NovaWebhooks\Traits\CreatedWebhook::bootCreatedWebhook
     * @covers \Dniccum\NovaWebhooks\Library\WebhookUtility::executeWebhook
     */
    public function correctly_configured_webhook_fires_upon_model_creation()
    {
        Queue::fake();

        Webhook::factory()
            ->create([
                'settings' => [
                    PageView::class.':created'
                ]
            ]);

        PageView::factory()
            ->create();

        $this->assertDatabaseCount('page_views', 1);
        Queue::assertPushed(PageView::$job);
    }

    /**
     * @test
     * @covers \Dniccum\NovaWebhooks\Traits\CreatedWebhook::bootCreatedWebhook
     * @covers \Dniccum\NovaWebhooks\Library\WebhookUtility::executeWebhook
     */
    public function incorrectly_configured_webhook_does_not_fire_upon_model_creation()
    {
        Queue::fake();

        Webhook::factory()
            ->create([
                'settings' => [
                    PageView::class.':updated'
                ]
            ]);

        PageView::factory()
            ->create();

        $this->assertDatabaseCount('page_views', 1);
        Queue::assertNotPushed(CallWebhookJob::class);
    }
}
