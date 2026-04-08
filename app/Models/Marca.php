<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'marca';
    protected $primaryKey = 'id_marca';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'logo',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}