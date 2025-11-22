<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'duration_days'];

    /**
     * Get the members for the plan.
     */
    public function members(): HasMany
    {
        // A Plan has many Members
        return $this->hasMany(Member::class);
    }
}
