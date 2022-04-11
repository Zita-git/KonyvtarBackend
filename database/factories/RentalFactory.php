<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "book_id"=>$this->faker->unique()->numberBetween(1,50),
            'start_date' => Carbon::now()->subDays($this->faker->numberBetween(7, 15)),
            'end_date'=> Carbon::now()->addDays($this->faker->numberBetween(7, 15)),
        ];
    }
}
