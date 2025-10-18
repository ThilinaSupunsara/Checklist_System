<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class HousekeeperController extends Controller
{
    /**
     * Display a list of the owner's housekeepers.
     */
    public function index()
    {
        /** @var \App\Models\User $owner */
        $owner = Auth::user();

        // Get the IDs of properties owned by the user
        $propertyIds = $owner->properties()->pluck('id');

        // Find all unique housekeeper IDs that have assignments for those properties
        $housekeeperIds = Assignment::whereIn('property_id', $propertyIds)
                                     ->distinct()
                                     ->pluck('housekeeper_id');

        // Fetch the User models for those housekeepers
        $housekeepers = User::whereIn('id', $housekeeperIds)->orderBy('name')->get();

        return view('housekeepers.index', compact('housekeepers'));
    }

    /**
     * Store a new housekeeper user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'housekeeper', // Automatically assign the correct role
        ]);

        return redirect()->route('owner.my-housekeepers.index')->with('success', 'Housekeeper invited successfully. You can now assign them to properties.');
    }

    /**
     * Remove the specified housekeeper's future assignments for the owner.
     */
    public function destroy(User $my_housekeeper)
    {
        /** @var \App\Models\User $owner */
        $owner = Auth::user();

        // Get the owner's property IDs
        $propertyIds = $owner->properties()->pluck('id');

        // Find and delete all FUTURE assignments for this housekeeper on the owner's properties
        Assignment::where('housekeeper_id', $my_housekeeper->id)
                  ->whereIn('property_id', $propertyIds)
                  ->where('scheduled_date', '>=', now()->toDateString())
                  ->delete();

        return redirect()->route('owner.my-housekeepers.index')->with('success', 'Housekeeper removed from future assignments.');
    }
}
