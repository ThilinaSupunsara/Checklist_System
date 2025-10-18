<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_item_id',
        'file_path',
        'timestamp',
        'gps_coordinates',
    ];

    /**
     * Get the checklist item that the photo belongs to.
     */
    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }
}
