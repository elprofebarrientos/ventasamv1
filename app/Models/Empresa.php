<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'empresa';

    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'nit',
        'digito_verificacion',
        'razon_social',
        'nombre_comercial',
        'direccion_fisica',
        'id_country',
        'id_department',
        'id_city',
        'telefono_contacto',
        'email_corporativo',
        'email_facturacion',
        'sitio_web',
        'representante_legal',
        'cedula_representante',
        'logo_empresa',
        'regimen_fiscal',
        'resolucion_dian',
        'rango_inicio',
        'rango_fin',
        'clave_tecnica',
    ];

    protected function casts(): array
    {
        return [
            'rango_inicio' => 'integer',
            'rango_fin' => 'integer',
        ];
    }

    /**
     * Get the country that owns the empresa.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'id_country', 'id_country');
    }

    /**
     * Get the department that owns the empresa.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department');
    }

    /**
     * Get the city that owns the empresa.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'id_city', 'id_city');
    }

    /**
     * Get the sucursales for the empresa.
     */
    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class, 'id_empresa', 'id_empresa');
    }
}
