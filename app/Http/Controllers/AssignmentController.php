<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'housekeeper_id' => 'required|exists:users,id',
            'scheduled_date' => 'required|date',
        ]);

        // Authorize: Ensure the property belongs to the logged-in owner
        $property = Property::findOrFail($validated['property_id']);
        if ($property->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Check for duplicate assignment
        $exists = Assignment::where('property_id', $validated['property_id'])
                            ->where('scheduled_date', $validated['scheduled_date'])
                            ->exists();

        if ($exists) {
            return back()->with('error', 'This property is already assigned for this date.');
        }

        Assignment::create($validated);

        return redirect()->route('owner.calendar.index')->with('success', 'Assignment created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        // Authorize: Ensure the assignment's property belongs to the owner
        if ($assignment->property->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $assignment->delete();

        return redirect()->route('owner.calendar.index')->with('success', 'Assignment deleted successfully.');
    }

    public function updateDate(Request $request, Assignment $assignment): JsonResponse
    {
        // Authorize: Ensure the assignment's property belongs to the owner
        if ($assignment->property->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'scheduled_date' => 'required|date',
        ]);

        $assignment->update(['scheduled_date' => $validated['scheduled_date']]);

        return response()->json(['message' => 'Assignment date updated successfully.']);
    }
}
