<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <div>
                <h2 class="page-header-title">{{ __('Schedule Assignments') }}</h2>
                <p class="page-header-subtitle">Create new assignments and manage your schedule on the calendar.</p>
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
            --danger-color: #ef4444;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }

        .management-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media (min-width: 1024px) {
            .management-grid { grid-template-columns: repeat(3, 1fr); }
            .main-content { grid-column: span 2 / span 2; }
            .sidebar { grid-column: span 1 / span 1; }
        }

        .panel { background-color: var(--panel-bg); padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); }
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.5rem; }

        /* Form styling */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); }
        .form-input, .form-select {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color);
            border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus, .form-select:focus {
            outline: none; border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); background-color: white;
        }

        .alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.5rem; font-weight: 500; }
        .alert--success { background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .alert--danger { background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* --- Custom FullCalendar Styles --- */
        #calendar { --fc-border-color: var(--border-color); --fc-today-bg-color: #f4f4f5; --fc-button-bg-color: var(--primary-color); --fc-button-border-color: var(--primary-color); --fc-button-hover-bg-color: #4338ca; --fc-button-hover-border-color: #4338ca; --fc-button-active-bg-color: #4338ca; --fc-button-active-border-color: #4338ca; }
        .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); }
        .fc .fc-daygrid-day-number { font-size: 0.875rem; color: var(--text-secondary); }
        .fc .fc-daygrid-event { border-radius: 4px; border: none; padding: 4px 6px; cursor: pointer; }
        .fc .fc-event-title { font-weight: 500; }
        .fc-event-main { transition: background-color 0.2s ease; }
        .fc-event-main:hover { filter: brightness(0.9); }

        /* Button styles */
        .button { display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 0.75rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }
        .button--danger { background-color: var(--danger-color); color: white; }
        .button--danger:hover { background-color: #dc2626; }
        .button--secondary { background-color: #6b7280; color: white; }
        .button--secondary:hover { background-color: #4b5563; }

        /* Modal styles */
        .modal-backdrop { position: fixed; inset: 0; background-color: rgba(17,24,39,0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 50; }
        .modal-content { background-color: white; border-radius: 1rem; padding: 2rem; width: 100%; max-width: 420px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); text-align: center; }
        .modal-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; border-radius: 50%; background-color: #fee2e2; display: flex; align-items: center; justify-content: center; color: var(--danger-color); }
        .modal-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
        .modal-text { color: var(--text-secondary); margin-bottom: 1.5rem; }
        .modal-actions { display: flex; gap: 1rem; justify-content: center; }
    </style>

    <div x-data="{
        showConfirmModal: false,
        deleteUrl: '',
        openModal(url) {
            this.deleteUrl = url;
            this.showConfirmModal = true;
        }
    }">
        <div class="container">
            <div class="management-grid">
                <div class="sidebar">
                    <div class="panel">
                        <h3 class="panel__title">Create New Assignment</h3>

                        @if (session('success'))
                            <div class="alert alert--success" role="alert"><span>{{ session('success') }}</span></div>
                        @endif
                        @if (session('error'))
                             <div class="alert alert--danger" role="alert"><span>{{ session('error') }}</span></div>
                        @endif

                        <form method="POST" action="{{ route('owner.assignments.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="property_id" class="form-label">{{ __('Property') }}</label>
                                <select id="property_id" name="property_id" class="form-select" required>
                                    <option value="">Select a property</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="housekeeper_id" class="form-label">{{ __('Housekeeper') }}</label>
                                <select id="housekeeper_id" name="housekeeper_id" class="form-select" required>
                                    <option value="">Select a housekeeper</option>
                                    @foreach($housekeepers as $housekeeper)
                                        <option value="{{ $housekeeper->id }}" {{ old('housekeeper_id') == $housekeeper->id ? 'selected' : '' }}>{{ $housekeeper->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="scheduled_date" class="form-label">{{ __('Date') }}</label>
                                <input id="scheduled_date" class="form-input" type="date" name="scheduled_date" value="{{ old('scheduled_date') }}" required />
                            </div>
                            <div class="mt-8">
                                <button type="submit" class="button button--primary">{{ __('Assign') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="main-content">
                    <div class="panel">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showConfirmModal" x-transition class="modal-backdrop" style="display: none;">
            <div @click.away="showConfirmModal = false" x-show="showConfirmModal" x-transition class="modal-content">
                <div class="modal-icon"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                <h3 class="modal-title">Delete Assignment?</h3>
                <p class="modal-text">Are you sure you want to permanently delete this assignment? This action cannot be undone.</p>
                <div class="modal-actions">
                    <button type="button" @click="showConfirmModal = false" class="button button--secondary">Cancel</button>
                    <form :action="deleteUrl" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="button button--danger">Delete Assignment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Add the JSDoc comment on the line below to fix the editor error
            /** @type {import('fullcalendar').Calendar} */
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                editable: true,
                events: '{{ route("api.calendar.events") }}',
                eventDrop: function(info) {
                    const assignmentId = info.event.id;
                    const newDate = info.event.start.toISOString().slice(0, 10);

                    fetch(`/api/assignments/${assignmentId}/update-date`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            scheduled_date: newDate
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            info.revert();
                            alert("Could not update the assignment date.");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        info.revert();
                    });
                }
            });
            calendar.render();
        } else {
            console.error("Calendar element #calendar not found!");
        }
    });
</script>
@endpush
</x-app-layout>
