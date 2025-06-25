<?php

namespace App\Filament\Pages;

use App\Filament\Resources\WidgetsResource\Widgets\PatientChartWidget;
use App\Filament\Resources\WidgetsResource\Widgets\UserChartWidget;
use App\Filament\Resources\WidgetsResource\Widgets\AppointmentsChartWidget;
use App\Http\Controllers\AppointmentsController;
use App\Models\Appointments;
use App\Models\User;
use Filament\Pages\Page as BaseDashboard;


class CustomDashboard extends BaseDashboard
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