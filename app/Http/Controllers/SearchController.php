<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Handle the search request and return results based on user role.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        $results = [];

        // Only perform search if a query is present
        if ($query) {
            if ($user->role === 'admin') {
                // Admins can search for any user or property
                $results['users'] = User::where('name', 'LIKE', "%{$query}%")
                                         ->orWhere('email', 'LIKE', "%{$query}%")
                                         ->get();

                $results['properties'] = Property::where('name', 'LIKE', "%{$query}%")
                                                  ->with('user') // Eager load the owner
                                                  ->get();
            }
            elseif ($user->role === 'owner') {
                // Owners can only search for their own properties and assigned housekeepers
                $results['properties'] = Property::where('user_id', Auth::user())
                                 ->where('name', 'like', "%{$query}%")
                                 ->get();

                $propertyIds = Property::where('user_id', Auth::user())->pluck('id');


                $housekeeperIds = Assignment::whereIn('property_id', $propertyIds)
                                             ->distinct()
                                             ->pluck('housekeeper_id');

                $results['housekeepers'] = User::whereIn('id', $housekeeperIds)
                                                ->where(function ($q) use ($query) {
                                                    $q->where('name', 'LIKE', "%{$query}%")
                                                      ->orWhere('email', 'LIKE', "%{$query}%");
                                                })
                                                ->get();
            }
        }

        return view('search.results', ['query' => $query, 'results' => $results]);
    }
}
