<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VarianteValor extends Model
{
    use HasFactory;

    protected $table = 'variante_valor';

    protected $fillable = [
        'id_variante',
        'id_valor',
    ];

    protected function casts(): array
    {
        return [
            'id_variante' => 'integer',
            'id_valor' => 'integer',
        ];
    }

    /**
     * Get the variante that owns the valor.
     */
    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante', 'id_variante');
    }

    /**
     * Get the atributo valor.
     */
    public function valor(): BelongsTo
    {
        return $this->belongsTo(AtributoValor::class, 'id_valor', 'id_valor');
    }
}
