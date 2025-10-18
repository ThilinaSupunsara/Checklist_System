<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class DefaultInventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::where('is_default', true)->orderBy('name_of_item')->get();
        return view('admin.defaults.inventory', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_of_item' => 'required|string|max:255',
            'expected_quantity' => 'required|integer|min:1',
        ]);

        InventoryItem::create([
            'name_of_item' => $request->name_of_item,
            'expected_quantity' => $request->expected_quantity,
            'is_default' => true,
        ]);

        return back()->with('success', 'Default inventory item created.');
    }

    public function destroy(InventoryItem $default_inventory)
    {
        if (!$default_inventory->is_default) {
            return back()->with('error', 'Unauthorized action.');
        }
        $default_inventory->delete();
        return back()->with('success', 'Default inventory item deleted.');
    }
}
