<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bodega extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'bodega';

    protected $primaryKey = 'id_bodega';

    protected $fillable = [
        'id_sucursal',
        'nombre',
        'codigo',
        'responsable',
        'estado',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the sucursal that owns the bodega.
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }
}
