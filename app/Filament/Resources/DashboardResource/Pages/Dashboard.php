<?php

namespace App\Filament\Pages;

use App\Filament\Resources\WidgetsResource\Widgets\Chart\PatientChartWidget;
use App\Filament\Resources\WidgetsResource\Widgets\Chart\UserChartWidget;
use App\Filament\Resources\WidgetsResource\Widgets\Bar\AppointmentsChartWidget;
use App\Http\Controllers\AppointmentsController;
use App\Models\Appointments;
use App\Models\User;
use Filament\Pages\Dashboard as BaseDashboard;


class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            UserChartWidget::class,
            PatientChartWidget::class,
            AppointmentsChartWidget::class,
        ];
    }
    public function getColumns(): int
    {
        return 2;
    }
}