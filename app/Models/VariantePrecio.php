<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantePrecio extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'variante_precio';

    protected $fillable = [
        'variante_id',
        'ultimo_costo',
        'margen_porcentaje',
        'precio_base',
        'precio_final',
        'fecha_actualizacion',
    ];

    protected function casts(): array
    {
        return [
            'ultimo_costo' => 'decimal:2',
            'margen_porcentaje' => 'decimal:2',
            'precio_base' => 'decimal:2',
            'precio_final' => 'decimal:2',
            'fecha_actualizacion' => 'datetime',
        ];
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariante::class, 'variante_id', 'id_variante');
    }

    public function calcularPrecioFinal(): float
    {
        if ($this->precio_base === null) {
            return 0;
        }

        $margen = $this->margen_porcentaje ?? 0;
        return (float) ($this->precio_base + ($this->precio_base * $margen / 100));
    }
}