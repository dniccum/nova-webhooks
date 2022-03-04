<?php

namespace Dniccum\NovaWebhooks\Database\Factories;

use Dniccum\NovaWebhooks\Models\WebhookLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WebhookLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'successful' => true,
            'created_at' => now(),
        ];
    }

    /**
     * @return WebhookLogFactory|\Dniccum\NovaWebhooks\Database\Factories\WebhookLogFactory.state
     */
    public function notFound()
    {
        return $this->state(function(array $attributes) {
            return [
                'successful' => false,
                'error_code' => '404',
                'error_message' => 'Page not found.'
            ];
        });
    }

    /**
     * @return WebhookLogFactory|\Dniccum\NovaWebhooks\Database\Factories\WebhookLogFactory.state
     */
    public function failed()
    {
        return $this->state(function(array $attributes) {
            return [
                'successful' => false,
                'error_code' => '422',
                'error_message' => $this->faker->text,
            ];
        });
    }
}
