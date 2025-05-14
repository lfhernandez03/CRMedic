<?php

namespace App\Mail;

use App\Models\Patients;
use Illuminate\Mail\Mailable;

class PatientWelcomeEmail extends Mailable
{
    public $patient;

    public function __construct(Patients $patient)
    {
        $this->patient = $patient;
    }

    public function build()
    {
        return $this->subject('Welcome to the Health System!')
            ->view('emails.welcome');  // Asegúrate de crear esta vista
    }
}
