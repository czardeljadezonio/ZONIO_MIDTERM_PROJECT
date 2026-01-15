<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
         'email', 
         'start_date',
         'photo', 
         'plan_id'];

    /**
     * Get the plan that owns the member.
     */
    public function plan(): BelongsTo
    {
        // A Member belongs to one Plan
        return $this->belongsTo(Plan::class);
    }
}
