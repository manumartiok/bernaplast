<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use Traducible;

    protected $fillable = [
        'orden',
        'nombre',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];
}
