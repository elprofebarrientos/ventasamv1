<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodigoCliente extends Model
{
    use HasFactory, Auditable;

    public $timestamps = false;

    protected $table = 'codigo_cliente';

    protected $fillable = [
        'codigo',
        'tipo_descuento',
        'valor',
        'tipo_uso',
        'max_usos',
        'usos_actuales',
        'fecha_inicio',
        'fecha_fin',
        'monto_minimo',
        'aplica_a',
        'producto_id',
        'producto_ids',
        'categoria_id',
        'categoria_ids',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'monto_minimo' => 'decimal:2',
            'usos_actuales' => 'integer',
            'max_usos' => 'integer',
            'activo' => 'boolean',
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'creado_en' => 'datetime',
            'actualizado_en' => 'datetime',
            'producto_ids' => 'array',
            'categoria_ids' => 'array',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id_producto');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }

    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public function scopeValido($query)
    {
        $now = now();
        return $query->where('activo', true)
            ->where('fecha_inicio', '<=', $now)
            ->where('fecha_fin', '>=', $now);
    }

    public function puedeUsarse(): bool
    {
        if (!$this->activo) {
            return false;
        }

        $now = now();
        if ($now < $this->fecha_inicio || $now > $this->fecha_fin) {
            return false;
        }

        if ($this->tipo_uso === 'unico' && $this->usos_actuales >= 1) {
            return false;
        }

        if ($this->max_usos !== null && $this->usos_actuales >= $this->max_usos) {
            return false;
        }

        return true;
    }

    public function calcularDescuento(float $monto): float
    {
        if (!$this->puedeUsarse()) {
            return 0;
        }

        if ($this->monto_minimo !== null && $monto < $this->monto_minimo) {
            return 0;
        }

        if ($this->tipo_descuento === 'porcentaje') {
            return ($monto * $this->valor) / 100;
        }

        return (float) $this->valor;
    }

    public function usar(): void
    {
        $this->increment('usos_actuales');
    }
}
