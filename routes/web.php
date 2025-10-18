<?php

use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DefaultInventoryController;
use App\Http\Controllers\Admin\DefaultRoomController;
use App\Http\Controllers\Admin\DefaultTaskController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HousekeeperController;
use App\Http\Controllers\HousekeeperDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Models\Room;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth\login');
});


Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    } elseif ($user->role === 'housekeeper') {
        return redirect()->route('housekeeper.dashboard'); // Add this redirect
    } else {
        return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // ... profile routes ...

    // Define the actual dashboard routes
    Route::get('/owner/dashboard', [DashboardController::class, 'ownerDashboard'])->name('owner.dashboard')->middleware('role:owner');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('role:admin');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes Group
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... other admin routes
    Route::resource('users', AdminUserController::class);
    Route::resource('properties', AdminPropertyController::class);
    // Add the new route for viewing checklists
    Route::get('checklists/{checklist}', [ChecklistController::class, 'showCompleted'])->name('checklists.show');

    Route::get('defaults', function() {
        $defaultRooms = Room::where('is_default', true)->orderBy('name')->get();
        $defaultTasks = Task::where('is_default', true)->with('room')->orderBy('description')->get();
        return view('admin.defaults.index', compact('defaultRooms', 'defaultTasks'));
    })->name('defaults.index');
    Route::resource('default-rooms', DefaultRoomController::class)->except(['create', 'show', 'edit', 'update']);
    Route::resource('default-tasks', DefaultTaskController::class)->except(['create', 'show', 'edit', 'update']);

    Route::resource('default-inventory', DefaultInventoryController::class)->except(['show']);
});

// Owner/User Routes Group
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::resource('properties', PropertyController::class);
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::resource('assignments', AssignmentController::class)->only(['store', 'destroy']);
    Route::get('checklists/{checklist}', [ChecklistController::class, 'showCompleted'])->name('checklists.show');
    Route::post('properties/{property}/store-defaults', [PropertyController::class, 'storeDefaults'])->name('properties.storeDefaults');

    // Add the new route for managing housekeepers
    Route::resource('my-housekeepers', HousekeeperController::class)->except(['create', 'show', 'edit', 'update']);
});

// Housekeeper Routes Group
Route::middleware(['auth', 'role:housekeeper'])->prefix('housekeeper')->name('housekeeper.')->group(function () {
    Route::get('/dashboard', [HousekeeperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/checklist/{assignment}', [ChecklistController::class, 'show'])->name('checklist.show');
    // We define the 'store' route here so the form has a valid action
    Route::post('/checklist', [ChecklistController::class, 'store'])->name('checklist.store');
});

Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/calendar-events', [CalendarController::class, 'getEvents'])->name('api.calendar.events');
    Route::patch('/assignments/{assignment}/update-date', [AssignmentController::class, 'updateDate'])->name('api.assignments.updateDate');
});
require __DIR__.'/auth.php';
