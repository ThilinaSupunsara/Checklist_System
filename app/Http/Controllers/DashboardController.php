<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Checklist;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with system-wide stats.
     */
    public function adminDashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalProperties' => Property::count(),
            'cleaningsThisMonth' => Checklist::where('status', 'completed')
                                             ->whereMonth('created_at', Carbon::now()->month)
                                             ->count(),
            'pendingAssignments' => Assignment::where('scheduled_date', '>=', Carbon::today())
                                              ->whereDoesntHave('checklist')
                                              ->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display the owner dashboard with user-specific stats.
     */
    public function ownerDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $propertyIds = $user->properties()->pluck('id');

        // Fetch the actual assignment objects for the next 7 days
        $upcomingAssignmentsList = Assignment::whereIn('property_id', $propertyIds)
            ->with(['property', 'housekeeper']) // Eager load relationships to prevent extra queries
            ->whereBetween('scheduled_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('scheduled_date', 'asc') // Order by the soonest date first
            ->get();

        $stats = [
            'totalProperties' => $propertyIds->count(),
            'upcomingAssignments' => Assignment::whereIn('property_id', $propertyIds)
                                               ->whereBetween('scheduled_date', [Carbon::today(), Carbon::today()->addDays(7)])
                                               ->count(),
            'totalHousekeepers' => Assignment::whereIn('property_id', $propertyIds)
                                             ->distinct('housekeeper_id')
                                             ->count('housekeeper_id'),
        ];

        return view('owner.dashboard', compact('stats','upcomingAssignmentsList'));

    }
}
