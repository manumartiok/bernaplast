<?php

namespace App\Http\Controllers;

use App\Mail\ContactoMail;
use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FormController extends Controller
{
    public function contacto(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'telefono' => 'required|string|max:100',
            'email'    => 'required|email|max:255',
            'asunto'   => 'nullable|string|max:255',
            'mensaje'  => 'nullable|string|max:5000',
        ], [
            'nombre.required'   => 'Ingresá tu nombre y apellido.',
            'telefono.required' => 'Ingresá tu teléfono.',
            'email.required'    => 'Ingresá tu email.',
            'email.email'       => 'El email no es válido.',
        ]);

        // Va a la dirección cargada en Contacto desde el admin
        $destino = Contacto::first()?->mail ?: config('mail.from.address');

        try {
            Mail::to($destino)->send(new ContactoMail($data));
        } catch (\Exception $e) {
            Log::error('Error al enviar el formulario de contacto: ' . $e->getMessage());

            return back()->withInput()->withErrors(['contacto' => 'No pudimos enviar la consulta. Intentá nuevamente.']);
        }

        return back()->with('success', 'Recibimos tu consulta. Te vamos a responder a la brevedad.');
    }
}
