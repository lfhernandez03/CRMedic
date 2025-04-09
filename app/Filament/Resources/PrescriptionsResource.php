<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrescriptionsResource\Pages;
use App\Models\Prescriptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PrescriptionsResource extends Resource
{
    protected static ?string $model = Prescriptions::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Medical Management';
    protected static ?string $label = 'Prescriptions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('treatment_id')
                ->relationship('treatment', 'title') // Mostrar el título en vez del ID
                ->label('Treatment')
                ->required()
                ->searchable(),

            Forms\Components\Textarea::make('medicines')
                ->label('Medicines')
                ->placeholder('e.g., Amoxicillin 500mg - 3 times a day for 7 days')
                ->required()
                ->rows(4)
                ->maxLength(5000),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->placeholder('Optional general description of the prescription')
                ->rows(3)
                ->nullable(),

            Forms\Components\Textarea::make('indications')
                ->label('Indications / Instructions')
                ->placeholder('e.g., Take with food. Do not skip doses.')
                ->rows(4)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('treatment.title')
                    ->label('Treatment')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('medicines')
                    ->label('Medicines')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
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
            'index' => Pages\ListPrescriptions::route('/'),
            'create' => Pages\CreatePrescriptions::route('/create'),
            'edit' => Pages\EditPrescriptions::route('/{record}/edit'),
        ];
    }
}
