<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $primaryKey = 'id_city';

    protected $fillable = [
        'id_department',
        'name',
        'code',
    ];

    /**
     * Get the department that owns the city.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department');
    }
}
