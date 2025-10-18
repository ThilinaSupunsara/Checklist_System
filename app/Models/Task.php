<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'description', 'is_default'];

    /**
     * Get the room that the task belongs to.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
