<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HousekeeperDashboardController extends Controller
{
    /**
     * Display the housekeeper's dashboard with upcoming and completed jobs.
     */
    public function index()
    {
        // Fetch all assignments for the logged-in housekeeper, eager loading relations
        $assignments = Assignment::where('housekeeper_id', Auth::id())
                                 ->with(['property', 'checklist'])
                                 ->orderBy('scheduled_date', 'desc')
                                 ->get();

        // Separate the assignments into two collections
        $upcomingAssignments = $assignments->filter(function ($assignment) {
            // A job is "upcoming" if it has no checklist record yet
            // and is scheduled for today or a future date.
            return is_null($assignment->checklist) && Carbon::parse($assignment->scheduled_date)->isToday() || Carbon::parse($assignment->scheduled_date)->isFuture();
        })->sortBy('scheduled_date');

        $completedAssignments = $assignments->filter(function ($assignment) {
            // A job is "completed" if it has a checklist associated with it.
            return !is_null($assignment->checklist);
        });

        return view('housekeepers.dashboard', compact('upcomingAssignments', 'completedAssignments'));
    }
}
