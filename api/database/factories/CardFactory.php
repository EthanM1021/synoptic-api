<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->bothify('#?#?????####??##'),
            '_fk_employee_id' => $this->faker->numberBetween(1, Employee::count()),
            'credit' => $this->faker->randomFloat('2', 0, 100)
        ];
    }
}
