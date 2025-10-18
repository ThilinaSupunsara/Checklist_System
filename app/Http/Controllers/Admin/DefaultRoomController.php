<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class DefaultRoomController extends Controller
{
    /**
     * Store a newly created default room in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:rooms,name,NULL,id,is_default,1']);

        Room::create([
            'name' => $request->name,
            'is_default' => true,
            'property_id' => null, // Not associated with any property
        ]);

        return back()->with('success', 'Default room created successfully.');
    }

    /**
     * Remove the specified default room from storage.
     */
    public function destroy(Room $default_room)
    {
        // Ensure we are only deleting a default room
        if (!$default_room->is_default) {
            return back()->with('error', 'This is not a default room.');
        }
        $default_room->delete();
        return back()->with('success', 'Default room deleted successfully.');
    }
}
