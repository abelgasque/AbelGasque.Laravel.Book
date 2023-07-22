<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->unique()->sentence(3, true),
            'description' => fake()->unique()->sentence(10, true),
            'price' => fake()->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200)
        ];
    }

    public function withAuthor()
    {
        return $this->state(function (array $attributes) {
            $author = Author::factory()->create();
            return [
                'idAuthor' => $author->id,
            ];
        });
    }
}
