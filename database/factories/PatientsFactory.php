<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PatientsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'birthdate' => $this->faker->date(),
            'address' => $this->faker->address(),
            'medical_history' => $this->faker->paragraph(3), // Historia médica ficticia
        ];
    }
}
