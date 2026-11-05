<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use Illuminate\Database\Eloquent\Model;

class Novedade extends Model
{
    use Traducible;

    protected $fillable = [
        'categoria_id',
        'orden',
        'fecha',
        'titulo',
        'foto',
        'texto',
        'active',
        'destacado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'active' => 'boolean',
        'destacado' => 'boolean',
    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
