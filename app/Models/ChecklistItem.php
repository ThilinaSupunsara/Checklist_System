<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = ['checklist_id', 'task_id', 'status', 'notes'];

    /**
     * Get the parent checklist.
     */
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    /**
     * Get the task associated with this item.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the photos for the checklist item.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ChecklistPhoto::class);
    }

    public function inventoryData() {
    return $this->hasMany(ChecklistInventoryData::class);
}
}
