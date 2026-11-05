<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use Traducible;

    protected $fillable = [
        'orden',
        'nombre',
        'foto',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];
}
