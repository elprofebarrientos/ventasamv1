<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'abonos';

    protected $fillable = [
        'id_compra',
        'monto',
        'monto_restante',
        'metodo_pago',
        'nota',
        'documento',
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
