<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <div>
                <h2 class="page-header-title">Checklist: {{ $assignment->property->name }}</h2>
                <p class="page-header-subtitle">Complete all tasks for each room to finish the assignment.</p>
            </div>
        </div>
    </x-slot>

    {{-- Custom CSS for the new design --}}
    <style>
        :root {
            --panel-bg: #ffffff;
            --page-bg: #f8f9fa;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-container { /* Unchanged */ }
        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }
        .checklist-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
        @media (min-width: 1024px) {
            .checklist-grid { grid-template-columns: repeat(3, 1fr); }
            .main-content { grid-column: span 2 / span 2; }
            .sidebar { grid-column: span 1 / span 1; position: sticky; top: 5rem; align-self: start; }
        }
        .main-content, .sidebar { display: flex; flex-direction: column; gap: 2rem; }

        .panel { background-color: var(--panel-bg); padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); }
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1rem; }

        /* Progress List */
        .progress-list { list-style: none; padding: 0; margin: 0; }
        .progress-item { display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; font-weight: 500; color: var(--text-secondary); transition: color 0.3s ease; }
        .progress-item.is-active { color: var(--primary-color); font-weight: 600; }
        .progress-item.is-complete { color: var(--text-primary); }
        .progress-item__indicator { width: 1.5rem; height: 1.5rem; border: 2px solid var(--border-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; }
        .progress-item.is-active .progress-item__indicator { border-color: var(--primary-color); background-color: #eef2ff; }
        .progress-item.is-complete .progress-item__indicator { border-color: var(--success-color); background-color: var(--success-color); color: white; }
        .progress-item .icon-check { display: none; }
        .progress-item.is-complete .icon-check { display: block; }

        /* GPS Info */
        .gps-info { display: flex; align-items: center; gap: 0.75rem; background-color: #f3f4f6; padding: 0.75rem 1rem; border-radius: 0.5rem; font-family: monospace; font-size: 0.875rem; color: var(--text-primary); }

        .alert--danger { background-color: #fee2e2; border-left: 4px solid var(--danger-color); color: #991b1b; padding: 1rem; margin-bottom: 1.5rem; border-radius: 0 0.5rem 0.5rem 0; }

        .room-title { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; }

        .task-card { background-color: var(--page-bg); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem; transition: all 0.2s ease; }
        .task-card:focus-within, .task-card:hover { border-color: var(--primary-color); background-color: #f9fafb; }
        .task-label { display: flex; align-items: center; gap: 1rem; cursor: pointer; }
        .task-checkbox { width: 1.25rem; height: 1.25rem; border-radius: 0.25rem; border-color: #9ca3af; text-indent: 0; }
        .task-description { flex-grow: 1; color: var(--text-primary); }
        .note-input-wrapper { margin-top: 0.75rem; padding-left: 2.25rem; }
        .note-input { width: 100%; border: none; background: transparent; border-bottom: 1px solid var(--border-color); padding: 0.5rem 0; font-size: 0.875rem; }
        .note-input:focus { outline: none; border-bottom-color: var(--primary-color); }

        .form-divider { margin: 2rem 0; border-color: var(--border-color); }
        .form-group { margin-top: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; }
        .label-optional { font-weight: 400; color: var(--text-secondary); }
        .file-input { font-size: 0.875rem; color: var(--text-secondary); }

        .form-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); }
        .empty-state { text-align: center; padding: 3rem; background-color: #f9fafb; border-radius: 1rem; }
        .empty-state-title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); }

        .button { display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .button:disabled { background-color: #d1d5db; cursor: not-allowed; }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover:not(:disabled) { background-color: #4338ca; }
        .button--secondary { background-color: #e5e7eb; color: var(--text-primary); }
        .button--secondary:hover:not(:disabled) { background-color: #d1d5db; }
        .button--success { background-color: var(--success-color); color: white; }
        .button--success:hover:not(:disabled) { background-color: #059669; }
    </style>

    <div class="container" x-data="checklistManager()">
        <div class="checklist-grid">
            <aside class="sidebar">
                <div class="panel">
                    <h3 class="panel__title">Checklist Progress</h3>
                    <ul class="progress-list">
                        @foreach ($assignment->property->rooms as $index => $room)
                            <li class="progress-item" :class="{ 'is-active': currentRoomIndex === {{ $index }}, 'is-complete': rooms[{{ $index }}] && Object.values(rooms[{{ $index }}].tasks).every(task => task.completed) }">
                                <div class="progress-item__indicator">
                                    <svg class="icon-check w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span>{{ $room->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="panel">
                    <h3 class="panel__title">Pre-Check Verification</h3>
                    <div class="gps-info">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span x-text="gpsCoords">Fetching...</span>
                    </div>
                </div>
            </aside>

            <div class="main-content">
                <div class="panel">
                    @if ($errors->any())
                        <div class="alert alert--danger">
                            <div>
                                <p class="font-bold">Please correct the following errors:</p>
                                <ul class="list-disc list-inside mt-1 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('housekeeper.checklist.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- These hidden inputs are unchanged --}}
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                        <input type="hidden" name="latitude" x-model="housekeeperLatitude">
                        <input type="hidden" name="longitude" x-model="housekeeperLongitude">

                        @forelse ($assignment->property->rooms as $index => $room)
                            <div x-show="currentRoomIndex === {{ $index }}" class="room-checklist">
                                <h3 class="room-title">{{ $room->name }}</h3>

                                @forelse ($room->tasks as $task)
                                    <div class="task-card">
                                        <label class="task-label">
                                            <input type="checkbox" name="tasks[{{ $task->id }}]" value="completed" class="task-checkbox" x-model="rooms[{{ $index }}].tasks[{{ $task->id }}].completed">
                                            <span class="task-description">{{ $task->description }}</span>
                                        </label>
                                        <div class="note-input-wrapper">
                                            <input type="text" name="notes[{{ $task->id }}]" class="note-input" placeholder="Add an optional note...">
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No tasks defined for this room.</p>
                                @endforelse

                                <hr class="form-divider">

                                <div class="form-group">
                                    <label for="photos_{{ $room->id }}" class="form-label">Upload Photos for {{ $room->name }} <span class="label-optional">(Min. 8)</span></label>
                                    <input type="file" name="photos[{{ $room->id }}][]" id="photos_{{ $room->id }}" multiple class="file-input" />
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <h3 class="empty-state-title">No Checklist Found</h3>
                                <p>The owner has not set up any rooms or tasks for this property yet.</p>
                            </div>
                        @endforelse

                        @if($assignment->property->rooms->isNotEmpty())
                            <div class="form-actions">
                                <button type="button" @click="prevRoom()" x-show="currentRoomIndex > 0" class="button button--secondary">Previous Room</button>
                                <div style="flex-grow: 1;"></div>
                                <button type="button" @click="nextRoom()" x-show="currentRoomIndex < rooms.length - 1" :disabled="!isCurrentRoomComplete()" class="button button--primary">Next Room</button>
                                <button type="submit" x-show="currentRoomIndex === rooms.length - 1" :disabled="!isCurrentRoomComplete()" class="button button--success">
                                    Complete & Submit Checklist
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- The original script block is unchanged as per your instructions --}}
<script>
    function checklistManager() {
        return {
            currentRoomIndex: 0,
            gpsCoords: 'Attempting to fetch location...',
            housekeeperLatitude: null,
            housekeeperLongitude: null,
            rooms: @json($roomsForJs),
            init() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.gpsCoords = `${position.coords.latitude}, ${position.coords.longitude}`;
                            this.housekeeperLatitude = position.coords.latitude;
                            this.housekeeperLongitude = position.coords.longitude;
                        },
                        () => { this.gpsCoords = 'Unable to retrieve your location.'; }
                    );
                } else {
                    this.gpsCoords = 'Geolocation is not supported by your browser.';
                }
            },
            nextRoom() { if (this.currentRoomIndex < this.rooms.length - 1) { this.currentRoomIndex++; } },
            prevRoom() { if (this.currentRoomIndex > 0) { this.currentRoomIndex--; } },
            isCurrentRoomComplete() {
                if (this.rooms.length === 0 || !this.rooms[this.currentRoomIndex]) return true;
                const currentTasks = Object.values(this.rooms[this.currentRoomIndex].tasks);
                if (currentTasks.length === 0) return true;
                return currentTasks.every(task => task.completed);
            }
        }
    }
</script>
