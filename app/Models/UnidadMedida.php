<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadMedida extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unidad_medida';
    protected $primaryKey = 'id_unidad_medida';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'abreviatura',
        'tipo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}