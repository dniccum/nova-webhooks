<?php

namespace Dniccum\NovaWebhooks\Tests\Feature;

use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Models\Webhook;
use Dniccum\NovaWebhooks\Tests\Models\Api\PageLike;
use Dniccum\NovaWebhooks\Tests\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Dniccum\NovaWebhooks\Tests\TestCase;
use Spatie\WebhookServer\CallWebhookJob;

class ModelTraitsTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * @test
     * @covers \Dniccum\NovaWebhooks\Traits\CreatedWebhook::bootCreatedWebhook
     * @covers \Dniccum\NovaWebhooks\Traits\CreatedWebhook::createdWebhookPayload
     * @covers \Dniccum\NovaWebhooks\Traits\UpdatedWebhook::bootUpdatedWebhook
     * @covers \Dniccum\NovaWebhooks\Traits\UpdatedWebhook::updatedWebhookPayload
     * @covers \Dniccum\NovaWebhooks\Traits\DeletedWebhook::bootDeletedWebhook
     * @covers \Dniccum\NovaWebhooks\Traits\DeletedWebhook::deletedWebhookPayload
     * @covers \Dniccum\NovaWebhooks\Traits\AllWebhooks
     */
    public function model_has_all_available_bootable_methods_and_payloads()
    {
        $this->assertTrue(trait_exists(\Dniccum\NovaWebhooks\Traits\CreatedWebhook::class));
        $this->assertTrue(trait_exists(\Dniccum\NovaWebhooks\Traits\UpdatedWebhook::class));
        $this->assertTrue(trait_exists(\Dniccum\NovaWebhooks\Traits\DeletedWebhook::class));
        $this->assertTrue(trait_exists(\Dniccum\NovaWebhooks\Traits\AllWebhooks::class));

        $this->assertTrue(method_exists(PageView::class, 'bootCreatedWebhook'));
        $this->assertTrue(method_exists(PageView::class, 'bootUpdatedWebhook'));
        $this->assertTrue(method_exists(PageView::class, 'bootDeletedWebhook'));

        $this->assertTrue(method_exists(PageView::class, 'createdWebhookPayload'));
        $this->assertTrue(method_exists(PageView::class, 'updatedWebhookPayload'));
        $this->assertTrue(method_exists(PageView::class, 'deletedWebhookPayload'));
    }
}
