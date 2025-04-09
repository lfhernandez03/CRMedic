<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationsResource\Pages;
use App\Models\Notifications;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'Medical Management';
    protected static ?string $label = 'Notifications';
    protected static ?string $pluralLabel = 'Notifications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Las notificaciones normalmente no se editan desde aquí.
                Forms\Components\TextInput::make('type')->disabled(),
                Forms\Components\Textarea::make('data')->label('Content')->disabled(),
                Forms\Components\TextInput::make('notifiable_type')->label('To')->disabled(),
                Forms\Components\TextInput::make('created_at')->label('Sent At')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('notifiable_type')
                    ->label('To')
                    ->sortable(),

                Tables\Columns\TextColumn::make('data')
                    ->label('Content')
                    ->limit(80)
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListNotifications::route('/'),
        ];
    }
}
