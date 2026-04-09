<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecepcionDetalle extends Model
{
    use HasFactory, Auditable;

    protected $table = 'recepciones_detalle';

    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_recepcion',
        'id_variante',
        'id_bodega',
        'id_ubicacion',
        'cantidad_recibida',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_recibida' => 'decimal:2',
        ];
    }

    public function recepcion(): BelongsTo
    {
        return $this->belongsTo(RecepcionCompra::class, 'id_recepcion', 'id_recepcion');
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante', 'id_variante');
    }

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'id_bodega', 'id_bodega');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }
}
