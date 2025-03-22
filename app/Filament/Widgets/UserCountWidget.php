<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class UserCountWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Registered Users', User::count())
                ->icon('heroicon-o-users')
                ->description('The total number of registered users.')
                ->color('primary'),
        ];
    }
}
