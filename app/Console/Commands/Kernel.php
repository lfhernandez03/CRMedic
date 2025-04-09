<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos personalizados de Artisan.
     */
    protected $commands = [
        \App\Console\Commands\SendAppointmentReminders::class,
    ];

    /**
     * Define la programación de tareas.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('appointments:remind')->dailyAt('07:00');
    }

    /**
     * Registra los comandos para Artisan.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
