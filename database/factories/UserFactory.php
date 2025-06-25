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
        // Cargar especialidades desde el JSON
        $json = file_get_contents(base_path('app/especialidades.json'));
        $data = json_decode($json, true);
        $specialities = collect($data['specialities'])->pluck('name')->toArray();

        // Seleccionar 1 a 3 especialidades aleatorias
        $userSpecialities = collect($specialities)->random(rand(1, 3))->values()->all();

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber(),
            'birthdate' => $this->faker->date('Y-m-d', '2005-01-01'),
            'rol' => $this->faker->randomElement(['admin', 'doctor']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'specialities' => $userSpecialities,
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
