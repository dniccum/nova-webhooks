<?php

namespace Dniccum\NovaWebhooks\Tests\Database\Factories\Api;

use Dniccum\NovaWebhooks\Tests\Models\Api\PageLike;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageLikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageLike::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page' => $this->faker->text(),
        ];
    }
}
