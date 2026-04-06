<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodigoUso extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'codigo_uso';

    protected $fillable = [
        'codigo_cliente_id',
        'venta_id',
        'cliente_id',
        'fecha_uso',
    ];

    protected function casts(): array
    {
        return [
            'fecha_uso' => 'datetime',
        ];
    }

    public function codigoCliente(): BelongsTo
    {
        return $this->belongsTo(CodigoCliente::class, 'codigo_cliente_id');
    }
}