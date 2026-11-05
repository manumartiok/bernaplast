<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use Illuminate\Database\Eloquent\Model;

class Calidade extends Model
{
    use Traducible;

    protected $fillable = [
        'texto',
        'pilares',
        'foto',
        'archivo',
        'titulo_certificados',
    ];

    protected $casts = [
        'pilares' => 'array',
    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];
}
