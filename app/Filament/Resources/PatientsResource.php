<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientsResource\Pages;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; // Importa el Mailable para el correo
use App\Models\Patients;

class PatientsResource extends Resource
{
    protected static ?string $model = Patients::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Patients Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('birthdate'),

                Forms\Components\TextInput::make('address')
                    ->maxLength(255),

                // 👇 MultiSelect cargando enfermedades CIE-10 desde JSON
                Forms\Components\Select::make('disease_ids')
                    ->label('Diagnosis (CIE-10)')
                    ->options(function () {
                        // Cargar el archivo JSON desde la ubicación correcta
                        $filePath = base_path('app/codes.json');  // Ruta correcta
                        $data = json_decode(file_get_contents($filePath), true);

                        // Filtrar los datos para asegurarnos de que 'code' y 'description' estén presentes
                        $filteredData = collect($data)->filter(function ($item) {
                            return isset($item['code']) && isset($item['description']);
                        });

                        // Convertir los datos a un formato adecuado para Filament (code => description)
                        return $filteredData->mapWithKeys(function ($item) {
                            return [$item['code'] => "{$item['code']} - {$item['description']}"];
                        });
                    })
                    ->preload()
                    ->searchable()
                    ->afterStateUpdated(function ($state, $set, $get, $component) {
                        $record = $component->getRecord();

                        // Obtener el texto de las enfermedades seleccionadas
                        $selectedDiseases = collect($state)->map(function ($code) {
                            $filePath = base_path('app/codes.json');
                            $data = json_decode(file_get_contents($filePath), true);
                            $disease = collect($data)->firstWhere('code', $code);
                            return $disease['description'] ?? '';
                        });

                        // Combinar las enfermedades seleccionadas con la historia médica
                        $medicalHistory = $get('medical_history');
                        $combinedHistory = $selectedDiseases->implode(', ') . (empty($medicalHistory) ? '' : '. ' . $medicalHistory);

                        // Actualizar el estado de medical_history con la combinación
                        $set('medical_history', $combinedHistory);
                    })
                    ->dehydrated(false)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('medical_history')
                    ->label('Resumen historia médica (opcional)')
                    ->nullable()
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('birthdate')->date()->sortable(),
                Tables\Columns\TextColumn::make('address')->searchable(),
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatients::route('/create'),
            'edit' => Pages\EditPatients::route('/{record}/edit'),
        ];
    }

    // Hook para enviar el correo después de crear un nuevo paciente
    public static function afterCreate(Patients $patient): void
    {
        // Enviar el correo de bienvenida al paciente
        Mail::to($patient->email)->send(new WelcomeEmail($patient)); // Enviar el correo de bienvenida
    }
}
