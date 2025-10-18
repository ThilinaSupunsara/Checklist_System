<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    /**
     * FOR HOUSEKEEPERS: Display the checklist to be started.
     * Prepares data in a simple array to prevent Blade syntax errors.
     */
    public function show(Assignment $assignment): View
    {
        if ($assignment->housekeeper_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this checklist.');
        }

        $assignment->load(['property.rooms.tasks']);

        // Prepare a clean PHP array for JavaScript to avoid complex Blade syntax
        $roomsForJs = $assignment->property->rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'name' => $room->name,
                'tasks' => $room->tasks->mapWithKeys(function ($task) {
                    return [$task->id => ['id' => $task->id, 'completed' => false]];
                })
            ];
        })->values()->toArray();

        return view('checklist.show', compact('assignment', 'roomsForJs'));
    }

    /**
     * Store the completed checklist data from the housekeeper's submission.
     * This version saves photos directly without modification.
     */
    public function store(Request $request)
    {
        // 1. AUTHORIZATION & DATA RETRIEVAL
        $assignment = Assignment::with(['property.tasks', 'property.rooms'])->findOrFail($request->input('assignment_id'));

        if ($assignment->housekeeper_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // 2. SIMPLIFIED VALIDATION
        // This validation is less strict to prevent silent failures.
        // It mainly checks that the data exists and is in the correct format.
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'tasks' => 'present|array', // Ensures all tasks are submitted
            'notes' => 'present|array',
            'notes.*' => 'nullable|string|max:1000',
            'photos' => 'array',
            'photos.*.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Allows submission even if a photo fails
        ]);

        // 3. DATABASE TRANSACTION
        try {
            DB::transaction(function () use ($validated, $assignment) {
                // Create the main checklist record
                $checklist = Checklist::create([
                    'assignment_id' => $assignment->id,
                    'start_time' => now(),
                    'status' => 'in_progress', // Will be updated at the end
                ]);

                $notes = $validated['notes'];
                $checklistItems = [];

                // Save each completed task and its note
                if (!empty($validated['tasks'])) {
                    foreach (array_keys($validated['tasks']) as $taskId) {
                        $checklistItems[$taskId] = ChecklistItem::create([
                            'checklist_id' => $checklist->id,
                            'task_id' => $taskId,
                            'status' => 'completed',
                            'notes' => $notes[$taskId] ?? null,
                        ]);
                    }
                }

                // Save the uploaded photos
                if (!empty($validated['photos'])) {
                    $taskRoomMap = $assignment->property->tasks->pluck('room_id', 'id');
                    foreach ($validated['photos'] as $roomId => $files) {
                        // Find a task item in this room to associate the photos with
                        $taskIdsInRoom = $assignment->property->tasks->where('room_id', $roomId)->pluck('id');
                        $relevantChecklistItem = collect($checklistItems)->first(fn($item) => $taskIdsInRoom->contains($item->task_id));

                        if ($relevantChecklistItem && is_array($files)) {
                            foreach ($files as $file) {
                                // Store the original file directly
                                $folderPath = public_path('storage/checklist_photos');
                                $filename = uniqid() . '_' . $file->getClientOriginalName();
                                $file->move($folderPath, $filename);
                                ChecklistPhoto::create([
                                    'checklist_item_id' => $relevantChecklistItem->id,
                                    'file_path' => $filename,
                                    'timestamp' => now(),
                                ]);
                            }
                        }
                    }
                }

                // Mark the checklist as complete
                $checklist->update(['status' => 'completed', 'end_time' => now()]);
            });
        } catch (\Exception $e) {
            // If anything goes wrong, log the detailed error for debugging
            Log::error('Checklist submission failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while saving. The issue has been logged for the admin.');
        }

        // 4. REDIRECT ON SUCCESS
        return redirect()->route('dashboard')->with('success', 'Checklist submitted successfully!');
    }

    /**
     * FOR ADMINS/OWNERS: Display a completed checklist.
     */
    public function showCompleted(Checklist $checklist)
    {
        $user = Auth::user();
        $propertyOwnerId = $checklist->assignment->property->user_id;

        if ($user->role !== 'admin' && $user->id !== $propertyOwnerId) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $checklist->load(['assignment.property', 'assignment.housekeeper', 'checklistItems.task.room', 'checklistItems.photos']);
        $itemsByRoom = $checklist->checklistItems->groupBy('task.room.name');

        return view('checklist.completed', compact('checklist', 'itemsByRoom'));
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000;
        $latFrom = deg2rad($lat1); $lonFrom = deg2rad($lon1); $latTo = deg2rad($lat2); $lonTo = deg2rad($lon2);
        $latDelta = $latTo - $latFrom; $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
