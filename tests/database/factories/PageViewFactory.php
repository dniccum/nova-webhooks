<?php

namespace Dniccum\NovaWebhooks\Tests\Database\Factories;

use Dniccum\NovaWebhooks\Tests\Models\PageView;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageViewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageView::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->domainName,
            'number_of_views' => $this->faker->numberBetween(3, 100),
        ];
    }
}
