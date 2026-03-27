<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoVariante extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'producto_variante';

    protected $primaryKey = 'id_variante';

    protected $fillable = [
        'id_producto',
        'sku',
        'codigo_barras',
        'imagenes_json',
        'activo',
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
}
