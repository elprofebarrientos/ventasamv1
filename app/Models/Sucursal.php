<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sucursal';

    protected $primaryKey = 'id_sucursal';

    protected $fillable = [
        'id_empresa',
        'nombre',
        'direccion',
        'telefono',
        'estado',
    ];

    /**
     * Get the empresa that owns the sucursal.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    /**
     * Get the bodegas for the sucursal.
     */
    public function bodegas(): HasMany
    {
        return $this->hasMany(Bodega::class, 'id_sucursal', 'id_sucursal');
    }
}
