<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioDisponible extends Model
{
    use HasFactory, Auditable;

    protected $table = 'inventario_disponible';

    protected $primaryKey = 'id_variante';

    public $incrementing = false;

    protected $fillable = [
        'id_variante',
        'stock_actual',
        'stock_reservado',
        'stock_disponible',
        'ultima_actualizacion',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'stock_actual' => 'decimal:2',
            'stock_reservado' => 'decimal:2',
            'stock_disponible' => 'decimal:2',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante', 'id_variante');
    }

    public function recalcular(): void
    {
        $this->stock_disponible = $this->stock_actual - $this->stock_reservado;
        $this->ultima_actualizacion = now();
        $this->save();
    }
}
