<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'id_categoria',
        'id_marca',
        'id_unidad_medida',
        'estado',
        'permite_venta',
        'permite_alquiler',
    ];

    protected function casts(): array
    {
        return [
            'permite_venta' => 'boolean',
            'permite_alquiler' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Producto $producto) {
            if (empty($producto->slug)) {
                $producto->slug = \Str::slug($producto->nombre);
            }
        });

        static::updating(function (Producto $producto) {
            if ($producto->isDirty('nombre')) {
                $producto->slug = \Str::slug($producto->nombre);
            }
        });
    }

    /**
     * Get the categoria that owns the producto.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Get the marca that owns the producto.
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id_marca');
    }

    /**
     * Get the unidad_medida that owns the producto.
     */
    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida', 'id_unidad_medida');
    }
}