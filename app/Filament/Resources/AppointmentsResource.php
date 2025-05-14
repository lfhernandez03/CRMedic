<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentsResource\Pages;
use App\Models\Appointments;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;


class AppointmentsResource extends Resource
{
    protected static ?string $model = Appointments::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Appointments Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'name')
                    ->required(fn(callable $get) => $get('context') === 'create'), // Solo requerido al crear

                Forms\Components\Select::make('doctor_id')
                    ->label('Doctor')
                    ->relationship('doctor', 'name')
                    ->required(fn(callable $get) => $get('context') === 'create') // Solo requerido al crear
                    ->reactive(),

                Forms\Components\DatePicker::make('date')
                    ->required(fn(callable $get) => $get('context') === 'create') // Solo requerido al crear
                    ->reactive()
                    ->native(false)
                    ->minDate(fn(callable $get) => $get('context') === 'create' ? now() : null) // Restringir fechas pasadas solo al crear
                    ->disabledDates(function (callable $get) {
                        if ($get('context') === 'edit') {
                            return []; // No deshabilitar fechas al editar
                        }

                        // Generar 6 meses de fechas desde hoy
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
                    ]),


                Forms\Components\Select::make('time')
                    ->label('Available Time')
                    ->options(function (callable $get) {
                        $doctorId = $get('doctor_id');
                        $date = $get('date');
                        

                        if (!$doctorId || !$date) {
                            return [];
                        }

                        $doctor = User::find($doctorId);
                        if (!$doctor || !$doctor->horario) {
                            return [];
                        }

                        [$horaInicio, $horaFin] = explode(' - ', $doctor->horario);
                        $inicio = Carbon::createFromTimeString($horaInicio);
                        $fin = Carbon::createFromTimeString($horaFin)->subMinutes(30);

                        // Generamos los bloques de horario disponibles en formato `H:i`
                        $bloques = collect(CarbonPeriod::create($inicio, '30 minutes', $fin))
                            ->map(fn($bloque) => $bloque->format('H:i'));

                        // Buscar horarios ya ocupados en la BD y asegurarse de que sean `H:i`
                        $ocupadas = Appointments::where('doctor_id', $doctorId)
                            ->whereDate('date', $date)
                            ->pluck('time')
                            ->map(fn($time) => Carbon::parse($time)->format('H:i'));

                        // Filtrar solo los horarios disponibles
                        $disponibles = $bloques->reject(fn($hora) => $ocupadas->contains($hora));

                        // Convertimos en formato clave-valor para Filament
                        return $disponibles->mapWithKeys(fn($hora) => [$hora => $hora]);
                    })
                    ->required(fn(callable $get) => $get('context') === 'create') // Solo requerido al crear
                    ->default(fn(callable $get) => $get('time')) // Mantener el valor actual al editar
                    ->reactive()
                    ->disabled(fn(callable $get) => !$get('doctor_id') || !$get('date'))
                    ->rules([
                        function (callable $get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $doctorId = $get('doctor_id');
                                $date = $get('date');
                                $appointmentId = $get('id'); // Obtener el ID de la cita actual
                                

                                if (!$doctorId || !$date || !$value) {
                                    return;
                                }

                                $exists = Appointments::where('doctor_id', $doctorId)
                                    ->whereDate('date', $date)
                                    ->whereTime('time', $value)
                                    ->when($appointmentId, function ($query, $id) {
                                        $query->where('id', '!=', $id);
                                    })
                                    ->exists();

                                if ($exists) {
                                    $fail('Este horario ya está ocupado por otra cita.');
                                }
                            };
                        },
                    ]),

                Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ])
                    ->label('Status')
                    ->reactive()
                    ->default('scheduled')
                    ->required(),

                Forms\Components\Textarea::make('reason')
                    ->label('Reason')
                    ->required(fn(callable $get) => $get('context') === 'create') // Solo requerido al crear
                    ->maxLength(5000),
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
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('time')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointments::route('/create'),
            'edit' => Pages\EditAppointments::route('/{record}/edit'),
        ];
    }
}
