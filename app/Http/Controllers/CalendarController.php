<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the assignment calendar for the owner.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get the IDs of properties owned by the user
        $propertyIds = $user->properties()->pluck('id');

        // Fetch assignments for those properties, eager loading relationships
        $assignments = Assignment::whereIn('property_id', $propertyIds)
            ->with(['property', 'housekeeper'])
            ->orderBy('scheduled_date', 'desc')
            ->get();

        // Fetch the owner's properties for the assignment form dropdown
        $properties = $user->properties()->orderBy('name')->get();

        // Fetch all available housekeepers for the dropdown
        $housekeepers = User::where('role', 'housekeeper')->orderBy('name')->get();

        return view('calendar.index', compact('assignments', 'properties', 'housekeepers'));
    }

    public function getEvents(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $propertyIds = $user->properties()->pluck('id');

        $assignments = Assignment::whereIn('property_id', $propertyIds)
            ->with(['property', 'housekeeper'])
            ->get();

        $events = $assignments->map(function ($assignment) {
            return [
                'id'    => $assignment->id,
                'title' => "{$assignment->property->name} - {$assignment->housekeeper->name}",
                'start' => $assignment->scheduled_date,
                'allDay' => true, // Make events appear as all-day blocks
            ];
        });

        return response()->json($events);
    }
}
