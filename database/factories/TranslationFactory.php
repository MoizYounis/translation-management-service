<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale' => $this->faker->randomElement(['en', 'fr', 'es', 'de']),
            'key' => Str::slug($this->faker->unique()->words(3, true), '_'),
            'value' => $this->faker->sentence,
            'tags' => [$this->faker->randomElement(['web', 'mobile', 'desktop'])],
            'cdn_ready' => $this->faker->boolean(20),
        ];
    }
}
