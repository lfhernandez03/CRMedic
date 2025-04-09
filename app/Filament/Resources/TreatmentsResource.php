<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentsResource\Pages;
use App\Models\Treatments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TreatmentsResource extends Resource
{
    protected static ?string $model = Treatments::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'Medical Management';
    protected static ?string $label = 'Treatments';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Treatment Title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('purpose')
                ->label('Purpose')
                ->rows(3)
                ->maxLength(2000),

            Forms\Components\Textarea::make('instructions')
                ->label('Instructions')
                ->rows(5)
                ->required()
                ->maxLength(5000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('purpose')
                    ->label('Purpose')
                    ->limit(40)
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
        return [
            // Aquí después puedes agregar el relation manager de prescriptions
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTreatments::route('/'),
            'create' => Pages\CreateTreatments::route('/create'),
            'edit' => Pages\EditTreatments::route('/{record}/edit'),
        ];
    }
}
