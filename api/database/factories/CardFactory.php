<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->bothify('???###???###???#'),
            '_fk_employee_id' => $this->faker->numberBetween(1, Employee::count()),
            'credit' => $this->faker->randomFloat('2', 0, 100)
        ];
    }
}
