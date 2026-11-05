<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PresupuestoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;  // nombre, telefono, email
    public $items; // ítems de la solicitud con su producto

    public function __construct($data, $items, private ?UploadedFile $archivo = null)
    {
        $this->data = $data;
        $this->items = $items;
    }

    public function build()
    {
        $mail = $this->subject('Nueva solicitud de presupuesto')
                     ->replyTo($this->data['email'], $this->data['nombre'])
                     ->view('emails.solicitud');

        if ($this->archivo) {
            $mail->attach($this->archivo->getRealPath(), [
                'as'   => $this->archivo->getClientOriginalName(),
                'mime' => $this->archivo->getMimeType(),
            ]);
        }

        return $mail;
    }
}
