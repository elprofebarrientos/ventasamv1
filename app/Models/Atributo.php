<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atributo extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'atributos';

    protected $primaryKey = 'id_atributo';

    protected $fillable = [
        'nombre',
        'tipo_visual',
        'estado',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'nombre' => 'string',
        ];
    }

    /**
     * Get the valores for the atributo.
     */
    public function valores(): HasMany
    {
        return $this->hasMany(AtributoValor::class, 'id_atributo', 'id_atributo');
    }
}