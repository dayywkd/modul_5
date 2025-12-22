<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    public function definition()
    {
        $title = $this->faker->sentence(3);
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();

        return [
            'category_id' => $category->id,
            'slug' => Str::slug($title) . '-' . Str::random(4),
            'movie_title' => $title,
            'description' => $this->faker->paragraph(),
            'studio' => $this->faker->word(),
            'seat' => $this->faker->bothify('A-##'),
            'show_time' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'price' => $this->faker->numberBetween(10000, 100000),
            'user_name' => $this->faker->name(),
            'event_name' => $this->faker->word(),
            'event_description' => $this->faker->paragraph(),
        ];
    }
}
