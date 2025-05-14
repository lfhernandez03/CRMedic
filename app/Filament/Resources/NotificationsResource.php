<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationsResource\Pages;
use App\Models\Notifications;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;
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
                // Campo Select para elegir entre Email o WhatsApp
                Forms\Components\Select::make('type')
                    ->label('Notification Type')
                    ->options([
                        'email' => 'Send via Email',
                        'whatsapp' => 'Send via WhatsApp',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Notification Type')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
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

    // Este es el método que se ejecutará después de crear una nueva notificación
    protected function afterCreate($data)
    {
        // Ejecutamos el comando Artisan para enviar los correos
        Artisan::call('appointments:remind');

        // Puedes añadir una confirmación o log para verificar que se ejecutó correctamente
        $this->info('Reminder emails have been sent.');
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
