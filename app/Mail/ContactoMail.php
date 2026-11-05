<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // recibirá los datos del formulario

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Nuevo mensaje de contacto' . (!empty($this->data['asunto']) ? ': ' . $this->data['asunto'] : ''))
                    ->replyTo($this->data['email'], $this->data['nombre'])
                    ->view('emails.contacto');
    }
}