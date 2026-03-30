<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bodega extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bodega';

    protected $primaryKey = 'id_bodega';

    protected $fillable = [
        'id_sucursal',
        'nombre',
        'codigo',
        'responsable',
        'estado',
    ];

    /**
     * Get the sucursal that owns the bodega.
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }
}
