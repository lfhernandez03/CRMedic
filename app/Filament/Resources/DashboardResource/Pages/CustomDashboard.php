<?php

namespace App\Filament\Pages;

use App\Filament\Resources\WidgetsResource\Widgets\PatientChartWidget;
use App\Filament\Resources\WidgetsResource\Widgets\UserChartWidget;
use App\Filament\Resources\WidgetsResource\Widgets\AppointmentsChartWidget;
use Filament\Pages\Dashboard;

class CustomDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.dashboard';
    
    protected static ?string $title = 'Dashboard';
    
    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            UserChartWidget::class,
            PatientChartWidget::class,
            AppointmentsChartWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}