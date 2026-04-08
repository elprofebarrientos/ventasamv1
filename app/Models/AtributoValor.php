<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtributoValor extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'atributo_valor';

    protected $primaryKey = 'id_valor';

    protected $fillable = [
        'id_atributo',
        'valor',
        'codigo_hex',
        'imagen_url',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'id_atributo' => 'integer',
        ];
    }

    /**
     * Get the atributo that owns the valor.
     */
    public function atributo(): BelongsTo
    {
        return $this->belongsTo(Atributo::class, 'id_atributo', 'id_atributo');
    }
}