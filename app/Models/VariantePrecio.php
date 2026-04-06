<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'precio_venta',
        'impuesto_ids',
        'fecha_actualizacion',
    ];

    protected function casts(): array
    {
        return [
            'ultimo_costo' => 'decimal:2',
            'margen_porcentaje' => 'decimal:2',
            'precio_base' => 'decimal:2',
            'precio_final' => 'decimal:2',
            'precio_venta' => 'decimal:2',
            'fecha_actualizacion' => 'datetime',
            'impuesto_ids' => 'array',
        ];
    }

    protected function precioFinal(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->precio_base ?? 0) * (1 + ($this->margen_porcentaje ?? 0) / 100)
        );
    }

    protected function precioVenta(): Attribute
    {
        return Attribute::make(
            get: function () {
                $precioFinal = ($this->precio_base ?? 0) * (1 + ($this->margen_porcentaje ?? 0) / 100);
                $totalImpuestos = 0;
                
                if (!empty($this->impuesto_ids)) {
                    foreach ($this->impuesto_ids as $impuestoId) {
                        $impuesto = Impuesto::find($impuestoId);
                        if ($impuesto && $impuesto->activo) {
                            if ($impuesto->tipo === 'porcentaje') {
                                $totalImpuestos += $precioFinal * $impuesto->valor / 100;
                            } else {
                                $totalImpuestos += $impuesto->valor;
                            }
                        }
                    }
                }
                
                return $precioFinal + $totalImpuestos;
            }
        );
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariante::class, 'variante_id', 'id_variante');
    }

    public function impuestos(): BelongsToMany
    {
        return $this->belongsToMany(Impuesto::class, 'variante_impuesto', 'variante_id', 'impuesto_id')
            ->withTimestamps();
    }

    public static function getUltimoCosto(int $varianteId): ?float
    {
        $detalle = CompraDetalle::where('id_variante', $varianteId)
            ->join('compras', 'compras.id_compra', '=', 'compras_detalle.id_compra')
            ->orderByDesc('compras.fecha_emision')
            ->select('compras_detalle.costo_unitario')
            ->first();

        return $detalle ? (float) $detalle->costo_unitario : null;
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