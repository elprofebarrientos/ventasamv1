<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $primaryKey = 'id_country';

    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'phone_code',
        'capital',
        'currency',
        'currency_code',
        'currency_symbol',
        'region',
        'subregion',
    ];

    /**
     * Get the departments for the country.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'id_country', 'id_country');
    }
}
