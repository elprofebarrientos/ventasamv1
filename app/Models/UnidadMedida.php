<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadMedida extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'unidad_medida';
    protected $primaryKey = 'id_unidad_medida';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'abreviatura',
        'tipo',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}