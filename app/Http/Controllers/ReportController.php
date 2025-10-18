<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display the report generation form.
     */
   public function index()
    {
        $user = Auth::user();
        $properties = null;
        $housekeepers = null;

        if ($user->role === 'admin') {
            $properties = Property::orderBy('name')->get();
            $housekeepers = User::where('role', 'housekeeper')->orderBy('name')->get();
        } elseif ($user->role === 'owner') {
            $properties = Property::where('user_id', $user->id)
            ->orderBy('name')
            ->get();
        }

        return view('reports.index', compact('properties', 'housekeepers'));
    }

    /**
     * Generate and display the report based on user input.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'property_id' => 'nullable|exists:properties,id',
            'housekeeper_id' => 'nullable|exists:users,id',
        ]);

        $user = Auth::user();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Start the query on completed checklists, eager loading all necessary relationships
        $query = Checklist::where('status', 'completed')
                          ->whereBetween('end_time', [$startDate, $endDate . ' 23:59:59'])
                          ->with(['assignment.property', 'assignment.housekeeper']);

        // Scope the query based on the user's role
        if ($user->role === 'owner') {
            $propertyIds = Property::where('user_id', $user->id)->pluck('id');

            // Use a more direct 'whereHas' to filter by the owner's properties
            $query->whereHas('assignment', function ($q) use ($propertyIds) {
                $q->whereIn('property_id', $propertyIds);
            });
        }

        // Apply additional filters from the form
        if ($request->filled('property_id')) {
            $query->whereHas('assignment', function ($q) use ($request) {
                $q->where('property_id', $request->property_id);
            });
        }

        if ($user->role === 'admin' && $request->filled('housekeeper_id')) {
            $query->whereHas('assignment', function ($q) use ($request) {
                $q->where('housekeeper_id', $request->housekeeper_id);
            });
        }

        $checklists = $query->orderBy('end_time', 'desc')->get();

        // Pass the original request inputs to the view to display them
        $reportData = $request->all();

        return view('reports.results', compact('checklists', 'reportData'));
    }
}
