<?php

namespace App\Filament\Resources\WidgetsResource\Widgets;

use App\Models\Patients;

class PatientChartWidget extends BaseChartWidget
{
    protected static ?string $chartId = 'patientChart';
    protected static ?string $heading = 'Total Patients';

    protected function getModelClass(): string
    {
        return Patients::class;
    }
}