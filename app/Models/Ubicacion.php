<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    use HasFactory, Auditable;

    protected $table = 'ubicaciones';

    protected $primaryKey = 'id_ubicacion';

    protected $fillable = [
        'id_bodega',
        'id_padre',
        'nombre',
        'tipo',
        'codigo',
        'estado',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'id_bodega', 'id_bodega');
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_padre', 'id_ubicacion');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(Ubicacion::class, 'id_padre', 'id_ubicacion');
    }
}
