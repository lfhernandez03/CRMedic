<?php

namespace App\Filament\Resources\WidgetsResource\Widgets;

use App\Models\Appointments;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AppointmentsChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'citasPorEstadoChart';
    protected static ?string $heading = 'Appointments by Status';

    protected function getOptions(): array
    {
        $statusList = ['scheduled', 'completed', 'cancelled'];
        $colors = ['#facc15', '#4ade80', '#f87171'];

        // Contamos las citas por estado
        $count = collect($statusList)->map(function ($status) {
            return Appointments::where('status', $status)->count();
        });

        return [
            'chart' => [
                'type' => 'bar',
                'toolbar' => ['show' => false],
                'height' => 300,
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 5,
                    'horizontal' => false,
                    'columnWidth' => '45%',
                ],
            ],
            'dataLabels' => ['enabled' => false],
            'series' => [
                [
                    'name' => 'Appointments',
                    'data' => $count->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $statusList,
                'labels' => ['style' => ['colors' => '#cbd5e1']],
            ],
            'colors' => $colors,
            'yaxis' => [
                'labels' => ['style' => ['colors' => '#cbd5e1']],
            ],
            'tooltip' => [
                'enabled' => true,
            ],
        ];
    }
}