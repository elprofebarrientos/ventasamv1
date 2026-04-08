<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoVariante extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'producto_variante';

    protected $primaryKey = 'id_variante';

    protected $fillable = [
        'id_producto',
        'sku',
        'codigo_barras',
        'imagenes_json',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'id_producto' => 'integer',
            'imagenes_json' => 'array',
            'activo' => 'boolean',
        ];
    }

    /**
     * Get the producto that owns the variante.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Get the valores for the variante.
     */
    public function valores(): BelongsToMany
    {
        return $this->belongsToMany(AtributoValor::class, 'variante_valor', 'id_variante', 'id_valor')
            ->withTimestamps();
    }

    /**
     * Get the impuestos for the variante.
     */
    public function impuestos(): BelongsToMany
    {
        return $this->belongsToMany(Impuesto::class, 'variante_impuesto', 'variante_id', 'impuesto_id')
            ->withTimestamps();
    }

    /**
     * Calculate total impuestos for a given amount.
     */
    public function calcularImpuestos(float $monto): float
    {
        return $this->impuestos->filter(fn ($i) => $i->activo)->sum(fn ($i) => $i->calcular($monto));
    }
}
