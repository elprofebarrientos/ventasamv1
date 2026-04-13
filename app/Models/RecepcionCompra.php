<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecepcionCompra extends Model
{
    use HasFactory, Auditable;

    protected $table = 'recepciones_compra';

    protected $primaryKey = 'id_recepcion';

    protected $fillable = [
        'id_compra',
        'fecha',
        'fecha_recepcion',
        'numero_recepcion',
        'observacion',
        'estado',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
            'fecha_recepcion' => 'date',
        ];
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id_compra');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(RecepcionDetalle::class, 'id_recepcion', 'id_recepcion');
    }
}
