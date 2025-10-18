<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'beds', 'baths', 'latitude', 'longitude'];

    /**
     * Get the owner of the property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the rooms for the property.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get the assignments for the property.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get all tasks for the property through its rooms.
     */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Room::class);
    }

    public function inventoryItems() {
        return $this->hasMany(InventoryItem::class);
    }
}
