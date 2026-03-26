<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atributo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'atributos';

    protected $primaryKey = 'id_atributo';

    protected $fillable = [
        'nombre',
        'tipo_visual',
    ];

    protected function casts(): array
    {
        return [
            'nombre' => 'string',
        ];
    }
}