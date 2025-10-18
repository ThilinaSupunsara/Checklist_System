<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;
    protected $fillable = ['property_id', 'name_of_item', 'expected_quantity', 'is_default'];

    public function property() {
        return $this->belongsTo(Property::class);
    }
}
