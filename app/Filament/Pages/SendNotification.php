<?php

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Notifications\AppointmentReminder;

class SendNotification extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static string $view = 'filament.pages.send-notification';

    public $user_id;
    public $message;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Select Patient')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required(),

                Textarea::make('message')
                    ->label('Notification Content')
                    ->required(),
            ])
            ->statePath('');
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $user = User::find($data['user_id']);
        if ($user) {
            $user->notify(new AppointmentReminder($data['message']));

            Notification::make()
                ->title('Notification sent successfully!')
                ->success()
                ->send();
        }
    }
}
