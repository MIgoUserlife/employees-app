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
            'name' => fake('uk_UA')->name(),
            'position_id' => Position::query()->inRandomOrder()->value('id'),
            'date_of_employment' => fake()->date('d.m.y'),
            'phone_number' => fake('uk_UA')->e164PhoneNumber(),
            'email' => fake('uk_UA')->email(),
            'salary' => fake()->randomFloat(2, 0, 500000),
        ];
    }
}
