<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word(),
            'locale' => $this->faker->randomElement(['en', 'fr', 'es', 'de', 'it']),
            'content' => $this->faker->sentence(),
            'tags' => $this->faker->randomElements(['mobile', 'desktop', 'web'], 2),
        ];
    }
}
