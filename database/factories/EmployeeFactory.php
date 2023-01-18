<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake('uk_UA')->unique()->name(),
            'position_id' => Position::query()->inRandomOrder()->value('id'),
            'date_of_employment' => fake()->date('d.m.y'),
            'phone_number' => fake('uk_UA')->unique()->e164PhoneNumber(),
            'email' => fake('uk_UA')->unique()->email(),
            'salary' => fake()->numberBetween(0, 500000),
        ];
    }
}
