<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Impuesto extends Model
{
    use HasFactory;

    protected $table = 'impuesto';

    protected $fillable = [
        'nombre',
        'tipo',
        'valor',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function calcular(float $monto): float
    {
        if (!$this->activo) {
            return 0;
        }

        if ($this->tipo === 'porcentaje') {
            return $monto * $this->valor / 100;
        }

        return (float) $this->valor;
    }

    public function variantes(): BelongsToMany
    {
        return $this->belongsToMany(ProductoVariante::class, 'variante_impuesto', 'impuesto_id', 'variante_id')
            ->withTimestamps();
    }
}