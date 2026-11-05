<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use Illuminate\Database\Eloquent\Model;

class Nosotro extends Model
{
    use Traducible;

    protected $fillable = [
        'foto',
        'titulo',
        'texto',
        'titulo_valores',
        'valores',
    ];

    protected $casts = [
        'valores' => 'array',
    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];
}
