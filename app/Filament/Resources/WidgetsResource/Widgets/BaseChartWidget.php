<?php

namespace App\Filament\Resources\WidgetsResource\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\User;
use Carbon\Carbon;

class BaseChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'totalUsersWidget';
    protected static ?string $heading = 'Total Users';

    protected function getModelClass(): string
    {
        return User::class; // Modelo por defecto
    }

    protected function getOptions(): array
    {
        $datesInfo = $this->getDatesInfo();
        $nuevosEstaSemana = $datesInfo['nuevosEstaSemana'];
        $nuevosUltimos14Dias = $datesInfo['nuevosUltimos14Dias'];

        $percentageChange = $this->getPercentageChange($nuevosEstaSemana, $nuevosUltimos14Dias);

        $modelClass = $this->getModelClass();
        $totalUsers = $modelClass::count();

        $sign = $percentageChange > 0 ? '↗' : ($percentageChange < 0 ? '↘' : '→');
        $subtitleColor = $percentageChange > 0 ? '#6ee7b7' : ($percentageChange < 0 ? '#f87171' : '#d1d5db');
        $color = $percentageChange > 0 ? '#4CAF50' : '#EF4444';

        return [
            'chart' => [
                'type' => 'area',
                'sparkline' => ['enabled' => true],
                'height' => 150,
            ],
            'series' => [
                [
                    'name' => 'Usuarios Nuevos',
                    'data' => [$nuevosUltimos14Dias, $nuevosEstaSemana],
                ],
            ],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 2,
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'light',
                    'gradientToColors' => [$color],
                    'shadeIntensity' => 1,
                    'type' => 'vertical',
                    'opacityFrom' => 0.3,
                    'opacityTo' => 0,
                    'stops' => [0, 90, 100],
                ],
            ],
            'colors' => [$color],
            'title' => [
                'text' => number_format($totalUsers),
                'offsetX' => 0,
                'style' => [
                    'fontSize' => '24px',
                    'fontWeight' => '600',
                    'color' => '#ffffff',
                ],
            ],
            'subtitle' => [
                'text' => "{$percentageChange}% incremento {$sign}",
                'offsetX' => 0,
                'style' => [
                    'fontSize' => '13px',
                    'color' => $subtitleColor,
                ],
            ],
            'tooltip' => [
                'enabled' => false,
            ],
            'labels' => [],
        ];
    }

    protected function getDatesInfo(): array
    {
        $hoy = Carbon::now();
        $hace7Dias = $hoy->copy()->subDays(7);
        $hace14Dias = $hoy->copy()->subDays(14);

        return [
            'nuevosEstaSemana' => $this->getUserCountBetweenDates($hace7Dias->startOfDay(), $hoy->endOfDay()),
            'nuevosUltimos14Dias' => $this->getUserCountBetweenDates($hace14Dias->startOfDay(), $hace7Dias->endOfDay()),
        ];
    }

    private function getUserCountBetweenDates(Carbon $startDate, Carbon $endDate): int
    {
        $modelClass = $this->getModelClass();
        return $modelClass::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    protected function getPercentageChange(int $currentCount, int $previousCount): string
    {
        if ($previousCount > 0) {
            return number_format((($currentCount - $previousCount) / $previousCount) * 100, 1);
        } elseif ($previousCount == 0 && $currentCount > 0) {
            return '100.0';
        }
        return '0.0';
    }
}