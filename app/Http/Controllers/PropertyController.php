<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */ // <-- FIX: Add this PHPDoc block
        $user = Auth::user();

        // The linter will now understand that $user has a 'properties' method
        $properties = $user->properties()->latest()->paginate(10);

        return view('owner.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'beds' => 'required|integer|min:0',
            'baths' => 'required|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $validated['user_id'] = Auth::id();
        Property::create($validated);


        // This line flashes the success message to the session
        return redirect()->route('owner.properties.index')->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
{
    $this->authorize('view', $property);

    $property->load('rooms.tasks', 'inventoryItems');

    $defaultRooms = Room::where('is_default', true)->with('tasks')->get();

    // Fetch default inventory items that aren't already added to the property
    $propertyInventoryNames = $property->inventoryItems->pluck('name_of_item');
    $defaultInventory = InventoryItem::where('is_default', true)
                                     ->whereNotIn('name_of_item', $propertyInventoryNames)
                                     ->get();

    return view('owner.properties.show', compact('property', 'defaultRooms', 'defaultInventory'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        return view('owner.properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'beds' => 'required|integer|min:0',
            'baths' => 'required|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $property->update($validated);

        return redirect()->route('owner.properties.index')->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        $property->delete();

        return redirect()->route('owner.properties.index')->with('success', 'Property deleted successfully.');
    }

    public function storeDefaults(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $request->validate([
            'default_room_ids' => 'required|array',
            'default_room_ids.*' => 'exists:rooms,id', // Ensure all IDs are valid rooms
        ]);

        // Use a transaction to ensure all or nothing is saved
        try {
            DB::transaction(function () use ($request, $property) {
                $selectedDefaultRooms = Room::with('tasks')
                    ->whereIn('id', $request->default_room_ids)
                    ->where('is_default', true)
                    ->get();

                foreach ($selectedDefaultRooms as $defaultRoom) {
                    // 1. Replicate the Room for the property
                    $newRoom = $defaultRoom->replicate()->fill([
                        'property_id' => $property->id,
                        'is_default' => false,
                    ]);
                    $newRoom->save();

                    // 2. Replicate each of its default tasks for the new room
                    foreach ($defaultRoom->tasks as $defaultTask) {
                        if ($defaultTask->is_default) {
                            $newTask = $defaultTask->replicate()->fill([
                                'room_id' => $newRoom->id,
                                'is_default' => false,
                            ]);
                            $newTask->save();
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while importing templates. Please try again.');
        }

        return back()->with('success', 'Templates imported successfully!');
    }
}
