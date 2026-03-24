<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
        'id_categoria_padre',
        'nivel',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'nivel' => 'integer',
            'id_categoria_padre' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Categoria $categoria) {
            if ($categoria->id_categoria_padre) {
                $padre = Categoria::find($categoria->id_categoria_padre);
                if ($padre) {
                    $categoria->nivel = $padre->nivel + 1;
                }
            }
        });

        static::updating(function (Categoria $categoria) {
            if ($categoria->isDirty('id_categoria_padre')) {
                if ($categoria->id_categoria_padre) {
                    $padre = Categoria::find($categoria->id_categoria_padre);
                    if ($padre) {
                        $categoria->nivel = $padre->nivel + 1;
                    }
                } else {
                    $categoria->nivel = 1;
                }
            }
        });
    }

    /**
     * Get the parent category (recursive relationship).
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria_padre', 'id_categoria');
    }

    /**
     * Get the child categories (recursive relationship).
     */
    public function hijos(): HasMany
    {
        return $this->hasMany(Categoria::class, 'id_categoria_padre', 'id_categoria');
    }
}