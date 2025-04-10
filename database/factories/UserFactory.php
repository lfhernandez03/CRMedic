<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber(),
            'birthdate' => $this->faker->date('Y-m-d', '2005-01-01'),
            'rol' => $this->faker->randomElement(['admin', 'doctor',]),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'speciality_id' => null, // Ajusta si tienes un modelo de especialidades
            'horario' => 'Lun-Vie 9:00am - 5:00pm',
            'pacientes_atendidos' => $this->faker->numberBetween(0, 100),
            'pacientes_pendientes' => $this->faker->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
