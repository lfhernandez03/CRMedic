<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentsResource\Pages;
use App\Filament\Resources\TreatmentsResource\RelationManagers;
use App\Models\Treatments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsResource extends Resource
{
    protected static ?string $model = Treatments::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Patients Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'attended' => 'Attended',
                    ]),
                Forms\Components\Select::make('patient_id')
                    ->required()
                    ->relationshipTo('patients', 'id', 'name'),
                Forms\Components\Select::make('doctor_id')
                    ->required()
                    ->relationshipTo('doctors', 'id', 'schedule'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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
        return [
            //
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
