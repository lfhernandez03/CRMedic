<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Mail\PatientWelcomeEmail; // Asegúrate de importar el Mailable correcto
use App\Models\Patients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function sendTestEmail()
    {
        // Obtén el primer paciente de la base de datos para probar
        $patient = Patients::first();

        if ($patient) {
            // Enviar el correo de bienvenida
            try {
                // Aquí estamos enviando el correo de bienvenida al paciente
                Mail::to($patient->email)->send(new PatientWelcomeEmail($patient));

                // Confirmar que el correo fue enviado
                return response()->json(['message' => 'Correo enviado con éxito']);
            } catch (\Exception $e) {
                // En caso de error, mostrar el error
                return response()->json(['error' => 'Error al enviar el correo: ' . $e->getMessage()], 500);
            }
        }

        // Si no se encuentra un paciente, mostrar un mensaje de error
        return response()->json(['error' => 'No se encontró un paciente para enviar el correo'], 404);
    }
}
