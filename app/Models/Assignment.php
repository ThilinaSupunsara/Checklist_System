<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'housekeeper_id', 'scheduled_date'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_date' => 'date', // Add this line
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function housekeeper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'housekeeper_id');
    }

    public function checklist(): HasOne
    {
        return $this->hasOne(Checklist::class);
    }
}
