<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use Traducible;

    protected $fillable = [
        'titulo_productos',
        'texto_productos',
        'foto_nosotros',
        'titulo_nosotros',
        'texto_nosotros',
        'titulo_novedades',
        'texto_novedades',
        'titulo_clientes',
        'texto_clientes',
    ];

    protected $casts = [

    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];
}
