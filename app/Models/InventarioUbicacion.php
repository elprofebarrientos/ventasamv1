<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioUbicacion extends Model
{
    use HasFactory, Auditable;

    protected $table = 'inventario_ubicacion';

    protected $primaryKey = 'id_inventario';

    protected $fillable = [
        'id_variante',
        'id_ubicacion',
        'stock_actual',
        'stock_reservado',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'stock_actual' => 'decimal:2',
            'stock_reservado' => 'decimal:2',
        ];
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante', 'id_variante');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    public function getStockDisponibleAttribute(): float
    {
        return (float) ($this->stock_actual - $this->stock_reservado);
    }
}
