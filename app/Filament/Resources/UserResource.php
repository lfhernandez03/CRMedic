<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Employees Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('birthdate'),

                Forms\Components\Select::make('rol')
                    ->required()
                    ->options([
                        'doctor' => 'Doctor',
                        'admin' => 'Admin',
                    ])
                    ->reactive(),

                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),

                Forms\Components\MultiSelect::make('specialities')
                    ->label('Especialidades Médicas')
                    ->options(function () {
                        $filePath = base_path('app/especialidades.json');
                        $data = json_decode(file_get_contents($filePath), true);
                        return collect($data['specialties'])
                            ->mapWithKeys(fn(string $spec) => [$spec => $spec])
                            ->toArray();
                    })
                    ->preload()
                    ->searchable()
                    ->afterStateUpdated(function ($state, $set) {
                        // Asegúrate de que el estado sea un array
                        $set('specialities', is_array($state) ? $state : []);
                    })
                    ->dehydrated(false)
                    ->columnSpanFull(),




                Forms\Components\Select::make('horario')
                    ->label('Schedule')
                    ->options([
                        '06:00 - 14:00' => '06:00 - 14:00',
                        '14:00 - 22:00' => '14:00 - 22:00',
                    ])
                    ->hidden(fn($get) => $get('rol') !== 'doctor')
                    ->required(fn($get) => $get('rol') === 'doctor'),

                Forms\Components\TextInput::make('pacientes_atendidos')
                    ->label('Patients Attended')
                    ->numeric()
                    ->default(0)
                    ->hidden(fn($get) => $get('rol') !== 'doctor'),

                Forms\Components\TextInput::make('pacientes_pendientes')
                    ->label('Patients Pending')
                    ->numeric()
                    ->default(0)
                    ->hidden(fn($get) => $get('rol') !== 'doctor'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('birthdate')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rol')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable(),

                Tables\Columns\TextColumn::make('specialities')
                    ->label('Specialities')
                    ->formatStateUsing(fn($state) => implode(', ', $state))
                    ->hidden(fn($record) => !$record || $record->rol !== 'doctor'),

                Tables\Columns\TextColumn::make('horario')
                    ->label('Schedule')
                    ->hidden(fn($record) => !$record || $record->rol !== 'doctor'),

                Tables\Columns\TextColumn::make('pacientes_atendidos')
                    ->label('Patients Attended')
                    ->sortable()
                    ->hidden(fn($record) => !$record || $record->rol !== 'doctor'),

                Tables\Columns\TextColumn::make('pacientes_pendientes')
                    ->label('Patients Pending')
                    ->sortable()
                    ->hidden(fn($record) => !$record || $record->rol !== 'doctor'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rol')
                    ->label('Filter by Role')
                    ->options([
                        'admin' => 'Admin',
                        'doctor' => 'Doctor',
                    ]),
            ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
