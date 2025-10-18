<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Room;
use Illuminate\Http\Request;

class DefaultTaskController extends Controller
{
    /**
     * Store a newly created default task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
        ]);

        // Ensure the selected room is a default room
        $room = Room::find($request->room_id);
        if (!$room || !$room->is_default) {
            return back()->withErrors(['room_id' => 'The selected room is not a valid default room.']);
        }

        Task::create([
            'description' => $request->description,
            'room_id' => $request->room_id,
            'is_default' => true,
        ]);

        return back()->with('success', 'Default task created successfully.');
    }

    /**
     * Remove the specified default task from storage.
     */
    public function destroy(Task $default_task)
    {
        if (!$default_task->is_default) {
            return back()->with('error', 'This is not a default task.');
        }
        $default_task->delete();
        return back()->with('success', 'Default task deleted successfully.');
    }
}
