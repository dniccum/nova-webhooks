<?php

namespace Dniccum\NovaWebhooks\Tests\Feature;

use Dniccum\NovaWebhooks\Library\ModelUtility;
use Dniccum\NovaWebhooks\Models\Webhook;
use Dniccum\NovaWebhooks\Tests\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Dniccum\NovaWebhooks\Tests\TestCase;
use Spatie\WebhookServer\CallWebhookJob;

class ModelUtilityTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * @test
     * @covers \Dniccum\NovaWebhooks\Library\ModelUtility::availableModelActions
     */
    public function can_retrieve_all_of_the_available_models()
    {
        $actions = ModelUtility::availableModelActions();

        $this->assertCount(1, $actions);
        $this->assertEquals(PageView::class, $actions[0]->name);
    }
}
