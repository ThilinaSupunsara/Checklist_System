<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <div>
                <h2 class="page-header-title">Manage Property</h2>
                <p class="page-header-subtitle">You are managing the checklist for: <strong>{{ $property->name }}</strong></p>
            </div>
            <a href="{{ route('owner.properties.index') }}" class="button button--secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Properties
            </a>
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
            --danger-color: #ef4444;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-container { display: flex; justify-content: space-between; align-items: center; }
        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }
        .management-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
        @media (min-width: 1024px) {
            .management-grid { grid-template-columns: repeat(3, 1fr); }
            .main-content { grid-column: span 2 / span 2; }
            .sidebar { grid-column: span 1 / span 1; }
        }
        .main-content, .sidebar { display: flex; flex-direction: column; gap: 2rem; }

        .panel { background-color: var(--panel-bg); padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); }
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.5rem; }

        .checklist-item, .task-item { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; }
        .checklist-item:not(:last-child) { border-bottom: 1px solid var(--border-color); }
        .checklist-item__info { display: flex; align-items: center; gap: 1rem; }
        .checklist-item__icon { color: var(--primary-color); }
        .checklist-item__name { font-weight: 600; color: var(--text-primary); }
        .task-list { list-style: none; padding-left: 3rem; margin: 0.5rem 0 0 0; }
        .task-item { padding: 0.5rem 0; }
        .task-item__description { color: var(--text-secondary); }

        .button--icon { background: none; border: none; cursor: pointer; padding: 0.5rem; color: var(--text-secondary); border-radius: 50%; transition: all 0.2s ease; }
        .button--icon:hover { color: var(--danger-color); background-color: #fee2e2; }

        .form-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background-color: #f9fafb; }

        .template-card {
            display: block; padding: 1rem; border: 1px solid var(--border-color); border-radius: 0.5rem;
            cursor: pointer; transition: all 0.2s ease;
        }
        .template-card:hover { border-color: var(--primary-color); background-color: #f9fafb; }
        .template-card-header { display: flex; align-items: flex-start; gap: 1rem; }
        .template-card-checkbox { flex-shrink: 0; margin-top: 0.25rem; }
        .template-card-title { font-weight: 600; color: var(--text-primary); }
        .template-card-tasks { font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem; }

        .button { display: inline-flex; align-items: center; justify-content: center; padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }
        .button--secondary { background-color: transparent; color: var(--text-secondary); }
        .button--secondary:hover { background-color: #f3f4f6; }
        .button--danger { background-color: var(--danger-color); color: white; }
        .button--danger:hover { background-color: #dc2626; }

        .modal-backdrop { position: fixed; inset: 0; background-color: rgba(17,24,39,0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 50; }
        .modal-content { background-color: white; border-radius: 1rem; padding: 2rem; width: 100%; max-width: 420px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); text-align: center; }
        .modal-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; border-radius: 50%; background-color: #fee2e2; display: flex; align-items: center; justify-content: center; color: var(--danger-color); }
        .modal-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
        .modal-text { color: var(--text-secondary); margin-bottom: 1.5rem; }
        .modal-actions { display: flex; gap: 1rem; justify-content: center; }
    </style>

    <div x-data="{ showConfirmModal: false, deleteUrl: '', modalTitle: '', modalMessage: '' }"
         @open-modal.window="showConfirmModal = true; deleteUrl = $event.detail.url; modalTitle = $event.detail.title; modalMessage = $event.detail.message;">
        <div class="container">
            <div class="management-grid">
                <div class="main-content">
                    <div class="panel">
                        <h3 class="panel__title">Current Checklist Setup</h3>
                        <div class="space-y-2">
                            @forelse ($property->rooms as $room)
                                <div class="checklist-item">
                                    <div class="checklist-item__info">
                                        <svg class="checklist-item__icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>
                                        <div>
                                            <p class="checklist-item__name">{{ $room->name }}</p>
                                            <ul class="task-list">
                                                @forelse ($room->tasks as $task)
                                                    <li class="task-item">
                                                        <span class="task-item__description">{{ $task->description }}</span>
                                                        {{-- Add delete button for individual task here if needed --}}
                                                    </li>
                                                @empty
                                                    <li class="task-item__description italic">No tasks assigned.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                    {{-- You can add a delete form for the room here --}}
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">No rooms or tasks have been added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="sidebar">
                    <div class="panel">
                        <h3 class="panel__title">Import from Template</h3>
                        <p class="text-sm text-gray-600 mb-4">Select default rooms to add to this property. Associated tasks will be included automatically.</p>
                        <form action="{{ route('owner.properties.storeDefaults', $property) }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                @forelse ($defaultRooms as $defaultRoom)
                                    <label for="room_{{ $defaultRoom->id }}" class="template-card">
                                        <div class="template-card-header">
                                            <input type="checkbox" name="default_room_ids[]" value="{{ $defaultRoom->id }}" id="room_{{ $defaultRoom->id }}" class="template-card-checkbox">
                                            <div>
                                                <span class="template-card-title">{{ $defaultRoom->name }}</span>
                                                <p class="template-card-tasks">Tasks: {{ $defaultRoom->tasks->pluck('description')->implode(', ') ?: 'None' }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-gray-500">No default room templates are available.</p>
                                @endforelse
                            </div>
                            @if($defaultRooms->isNotEmpty())
                                <div class="flex justify-end mt-6">
                                    <button type="submit" class="button button--primary">{{ __('Import Selected') }}</button>
                                </div>
                            @endif
                        </form>
                    </div>

                    
                </div>
            </div>
        </div>

        <div x-show="showConfirmModal" x-transition class="modal-backdrop" style="display: none;">
            <div @click.away="showConfirmModal = false" x-show="showConfirmModal" x-transition class="modal-content">
                <div class="modal-icon"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                <h3 class="modal-title" x-text="modalTitle"></h3>
                <p class="modal-text" x-text="modalMessage"></p>
                <div class="modal-actions">
                    <button type="button" @click="showConfirmModal = false" class="button button--secondary">Cancel</button>
                    <form :action="deleteUrl" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="button button--danger">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
