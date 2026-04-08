<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Abono extends Model
{
    use HasFactory, Auditable;

    protected $table = 'abonos';

    protected $fillable = [
        'id_compra',
        'monto',
        'monto_restante',
        'metodo_pago',
        'nota',
        'documento',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'monto_restante' => 'decimal:2',
        ];
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id_compra');
    }
}
