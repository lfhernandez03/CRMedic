<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportsResource\Pages;
use App\Models\Reports;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

class ReportsResource extends Resource
{
    protected static ?string $model = Reports::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Medical Management';
    protected static ?string $label = 'Reports';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('patient_id')
                ->label('Patient')
                ->relationship('patient', 'name')
                ->required(),

            Forms\Components\Select::make('doctor_id')
                ->label('Doctor')
                ->relationship('doctor', 'name')
                ->required(),

            Forms\Components\DatePicker::make('date')
                ->required()
                ->reactive()
                ->native(false)
                ->minDate(now()) // No permitir fechas pasadas
                ->disabledDates(function () {
                    // Generamos 6 meses de fechas desde hoy
                    return collect(range(0, 180))
                        ->map(function ($i) {
                            $date = Carbon::today()->addDays($i);
                            // Si es sábado (6) o domingo (7), lo marcamos como inválido
                            if (in_array($date->dayOfWeekIso, [6, 7])) {
                                return $date->toDateString();
                            }
                            return null;
                        })
                        ->filter()
                        ->values()
                        ->toArray();
                })
                ->rules([
                    function () {
                        return function (string $attribute, $value, \Closure $fail) {
                            $dayOfWeek = Carbon::parse($value)->dayOfWeekIso;
                            if ($dayOfWeek > 5) {
                                $fail('Solo puedes seleccionar días de lunes a viernes.');
                            }
                        };
                    },
                ])
                ->hint('Selecciona solo días de lunes a viernes'),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->required()
                ->maxLength(10000),

            Forms\Components\Select::make('report_type')
                ->label('Report Type')
                ->options([
                    'patients_attended' => 'Patients Attended',
                    'diagnosis' => 'Diagnosis',
                    'lab_results' => 'Lab Results',
                    'consultation_summary' => 'Consultation Summary',
                ])
                ->required(),

            Forms\Components\Textarea::make('data')
                ->label('Data')
                ->rows(4)
                ->nullable(),

            Forms\Components\Select::make('specialty_id')
                ->label('Specialty')
                ->relationship('specialty', 'name')
                ->nullable(),


            Forms\Components\TimePicker::make('time')
                ->label('Time')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'open' => 'Open',
                    'in_review' => 'In Review',
                    'closed' => 'Closed',
                ])
                ->default('open')
                ->required(),

            Forms\Components\Textarea::make('reason')
                ->label('Report Notes / Reason')
                ->required()
                ->maxLength(5000),

            Forms\Components\Toggle::make('notificated')
                ->label('Notified'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'open' => 'warning',
                        'in_review' => 'info',
                        'closed' => 'success',
                    ])
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_review' => 'In Review',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReports::route('/create'),
            'edit' => Pages\EditReports::route('/{record}/edit'),
        ];
    }
}
