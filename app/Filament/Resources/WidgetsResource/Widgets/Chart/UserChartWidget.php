<?php
namespace App\Filament\Resources\WidgetsResource\Widgets\Chart;

use App\Models\User;

class UserChartWidget extends BaseChartWidget
{
    protected static ?string $chartId = 'userChart';
    protected static ?string $heading = 'Total Users';

    protected function getModelClass(): string
    {
        return User::class;
    }
}