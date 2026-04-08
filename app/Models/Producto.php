<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes, Auditable;

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
        'created_by',
        'updated_by',
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

        static::deleting(function (Producto $producto) {
            $producto->variantes()->forceDelete();
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

    /**
     * Get the variantes for the producto.
     */
    public function variantes(): HasMany
    {
        return $this->hasMany(ProductoVariante::class, 'id_producto', 'id_producto');
    }
}