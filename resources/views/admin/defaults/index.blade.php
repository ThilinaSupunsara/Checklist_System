<x-app-layout>

    {{-- Custom CSS for the new design --}}
    <style>
        :root {
            --panel-bg: #ffffff;
            --page-bg: #f8f9fa;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #e5e7eb;
            --border-color-focus: #4f46e5;
            --primary-color: #4f46e5;
            --danger-color: #ef4444;
            --danger-bg-light: #fee2e2;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }

        .management-container {
            padding: 2rem 1.5rem; max-width: 80rem; margin: auto;
            display: grid; grid-template-columns: 1fr; gap: 2rem;
        }
        @media (min-width: 1024px) { .management-container { grid-template-columns: repeat(2, 1fr); } }

        .panel { background-color: var(--panel-bg); padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); }
        .panel__header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color); }
        .panel__icon { color: var(--primary-color); }
        .panel__title { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); }

        /* --- UPGRADED FORM STYLES --- */
        .form-input, .form-select {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;
            font-size: 1rem; transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-input:focus, .form-select:focus {
            outline: none; border-color: var(--border-color-focus);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        .form-group { margin-bottom: 1rem; }

        /* Smart Error Styling */
        .form-error-message {
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;
        }
        .form-input.is-invalid, .form-select.is-invalid {
            border-color: var(--danger-color);
        }
        .form-input.is-invalid:focus, .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .form-inline { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
        .form-inline > .form-input { flex-grow: 1; }

        .button {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.75rem 1.25rem; border: none; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem; color: #ffffff;
            text-decoration: none; cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); }
        .button--primary:hover { background-color: #4338ca; }
        .button--danger { background-color: var(--danger-color); }
        .button--danger:hover { background-color: #dc2626; }
        .button--secondary { background-color: #6b7280; color: white; }
        .button--secondary:hover { background-color: #4b5563; }

        .item-list { list-style: none; padding: 0; margin: 0; }
        .item-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0.5rem; }
        .item-row:not(:last-child) { border-bottom: 1px solid var(--border-color); }
        .item-row__name { font-weight: 500; color: var(--text-primary); }
        .item-row__description { font-size: 0.875rem; color: var(--text-secondary); }

        .button--danger-icon {
            background: none; border: none; cursor: pointer; padding: 0.5rem;
            color: var(--text-secondary); border-radius: 50%;
            transition: color 0.2s ease, background-color 0.2s ease;
        }
        .button--danger-icon:hover { color: var(--danger-color); background-color: var(--danger-bg-light); }

        .empty-state { text-align: center; padding: 2rem; color: var(--text-secondary); }

        /* --- SMART CONFIRMATION MODAL STYLES --- */
        .modal-backdrop {
            position: fixed; inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center;
            z-index: 50;
        }
        .modal-content {
            background-color: white; border-radius: 1rem;
            padding: 2rem; width: 100%; max-width: 420px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .modal-icon {
            width: 3rem; height: 3rem; margin: 0 auto 1rem;
            border-radius: 50%; background-color: var(--danger-bg-light);
            display: flex; align-items: center; justify-content: center;
            color: var(--danger-color);
        }
        .modal-title { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; }
        .modal-text { font-size: 1rem; color: var(--text-secondary); margin-bottom: 1.5rem; }
        .modal-actions { display: flex; gap: 1rem; justify-content: center; }
    </style>

    {{-- ALPINE.JS DATA FOR MODAL CONTROL --}}
    <div x-data="{
        showConfirmModal: false,
        deleteUrl: '',
        modalTitle: '',
        modalMessage: '',
        openModal(url, title, message) {
            this.deleteUrl = url;
            this.modalTitle = title;
            this.modalMessage = message;
            this.showConfirmModal = true;
        }
    }">

        <div class="management-container">
            <div class="panel">
                <div class="panel__header">
                    <svg class="panel__icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 6V4a2 2 0 10-4 0v2"></path></svg>
                    <h3 class="panel__title">Default Rooms</h3>
                </div>

                <form action="{{ route('admin.default-rooms.store') }}" method="POST" class="form-inline">
                    @csrf
                    <input id="name" name="name" type="text" class="form-input @error('name') is-invalid @enderror" placeholder="Enter new room name" required />
                    <button type="submit" class="button button--primary">Add</button>
                </form>
                @error('name')
                    <p class="form-error-message">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror

                <ul class="item-list">
                    @forelse ($defaultRooms as $room)
                        <li class="item-row">
                            <span class="item-row__name">{{ $room->name }}</span>
                            <button type="button" @click="openModal('{{ route('admin.default-rooms.destroy', $room) }}', 'Delete Room?', 'Are you sure you want to permanently delete this room? This action cannot be undone.')" class="button--danger-icon" aria-label="Delete room">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </li>
                    @empty
                        <li class="empty-state">No default rooms found.</li>
                    @endforelse
                </ul>
            </div>

            <div class="panel">
                <div class="panel__header">
                    <svg class="panel__icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <h3 class="panel__title">Default Tasks</h3>
                </div>

                <form action="{{ route('admin.default-tasks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="form-group">
                        <input id="description" name="description" type="text" class="form-input @error('description') is-invalid @enderror" placeholder="Enter new task description" required />
                        @error('description')
                           <p class="form-error-message">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                               <span>{{ $message }}</span>
                           </p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <select name="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                            <option value="">Assign to a default room...</option>
                            @foreach ($defaultRooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <p class="form-error-message">
                               <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                               <span>{{ $message }}</span>
                           </p>
                        @enderror
                    </div>
                    <div style="text-align: right;">
                        <button type="submit" class="button button--primary">Add Task</button>
                    </div>
                </form>

                <ul class="item-list mt-6">
                    @forelse ($defaultTasks as $task)
                        <li class="item-row">
                            <div>
                                <p class="item-row__name">{{ $task->description }}</p>
                                <p class="item-row__description">In: {{ $task->room->name ?? 'N/A' }}</p>
                            </div>
                            <button type="button" @click="openModal('{{ route('admin.default-tasks.destroy', $task) }}', 'Delete Task?', 'Are you sure you want to permanently delete this task? This cannot be undone.')" class="button--danger-icon" aria-label="Delete task">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </li>
                    @empty
                        <li class="empty-state">No default tasks found.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div x-show="showConfirmModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="modal-backdrop" style="display: none;">
            <div @click.away="showConfirmModal = false" x-show="showConfirmModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="modal-content">
                <div class="modal-icon">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="modal-title" x-text="modalTitle"></h3>
                <p class="modal-text" x-text="modalMessage"></p>
                <div class="modal-actions">
                    <button type="button" @click="showConfirmModal = false" class="button button--secondary">Cancel</button>
                    <form :action="deleteUrl" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button button--danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
