<?php

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Notifications\AppointmentReminder;
use App\Mail\WelcomeEmail; // Importamos el Mailable
use Illuminate\Support\Facades\Mail;

class SendNotification extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationLabel = 'Send Notification';
    protected static ?string $navigationGroup = 'Medical Management';
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
            ]);
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $user = User::find($data['user_id']);
        if ($user) {
            // Enviar la notificación de recordatorio de cita
            $user->notify(new AppointmentReminder($data['message']));

            // Llamamos al método para enviar el correo de bienvenida
            $this->sendWelcomeEmail($user);

            // Notificación de éxito
            Notification::make()
                ->title('Notification sent successfully!')
                ->success()
                ->send();
        }
    }

    public function sendWelcomeEmail(User $user)
    {
        // Enviar el correo de bienvenida al nuevo usuario
        Mail::to($user->email)->send(new WelcomeEmail($user));
    }
}
