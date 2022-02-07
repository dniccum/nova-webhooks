<?php

namespace Dniccum\NovaWebhooks\Database\Factories;

use Dniccum\NovaWebhooks\Models\Webhook;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Webhook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text,
            'url' => $this->faker->url,
            'settings' => [],
        ];
    }
}
