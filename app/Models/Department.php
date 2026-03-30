<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $primaryKey = 'id_department';

    protected $fillable = [
        'id_country',
        'name',
        'code',
    ];

    /**
     * Get the country that owns the department.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'id_country', 'id_country');
    }

    /**
     * Get the cities for the department.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'id_department', 'id_department');
    }
}
