<?php

namespace Dniccum\NovaWebhooks\Tests\Feature;

use Dniccum\NovaWebhooks\Library\ModelUtility;
use Dniccum\NovaWebhooks\Tests\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Dniccum\NovaWebhooks\Tests\TestCase;

class ModelUtilityTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * @test
     * @covers \Dniccum\NovaWebhooks\Library\ModelUtility::availableModelActions
     * @covers \Dniccum\NovaWebhooks\Library\ModelUtility::getModels
     */
    public function can_retrieve_all_of_the_available_models()
    {
        $actions = ModelUtility::availableModelActions();

        $this->assertCount(2, $actions);
        $this->assertStringContainsString(PageView::class, $actions[1]->name);
    }
}
