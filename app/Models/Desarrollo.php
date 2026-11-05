<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use Illuminate\Database\Eloquent\Model;

class Desarrollo extends Model
{
    use Traducible;

    protected $fillable = [
        'foto',
        'texto',
        'pasos',
        'texto2',
        'titulo_matricerias',
    ];

    protected $casts = [
        'pasos' => 'array',
    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];
}
