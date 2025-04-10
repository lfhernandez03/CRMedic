<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador Secundario',
            'email' => 'admin2@clinica.com',
            'password' => bcrypt('admin123'),
            'phone' => '3000000000',
            'birthdate' => '1985-01-01',
            'rol' => 'admin',
            'status' => 'active',
            'speciality_id' => null,
            'horario' => 'Lun-Vie 9:00am - 5:00pm',
            'pacientes_atendidos' => 0,
            'pacientes_pendientes' => 0,
        ]);

        // Generar 50 usuarios adicionales
        User::factory(50)->create();
    }
}
