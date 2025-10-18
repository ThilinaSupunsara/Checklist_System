<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <div>
                <h2 class="page-header-title">{{ __('My Schedule') }}</h2>
                <p class="page-header-subtitle">View your upcoming and completed cleaning assignments.</p>
            </div>
        </div>
    </x-slot>

    {{-- FullCalendar CSS (Required for the calendar to render) --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/main.min.css' rel='stylesheet' />

    {{-- Custom CSS for the design --}}
    <style>
        :root {
            --panel-bg: #ffffff;
            --page-bg: #f8f9fa;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-container { display: flex; justify-content: space-between; align-items: center; }
        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }

        /* --- TWO-COLUMN LAYOUT --- */
        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media (min-width: 1024px) {
            .schedule-grid { grid-template-columns: repeat(3, 1fr); }
            .main-content { grid-column: span 2 / span 2; }
            .sidebar { grid-column: span 1 / span 1; }
        }
        .sidebar { display: flex; flex-direction: column; gap: 2rem; }

        .panel { background-color: var(--panel-bg); border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); overflow: hidden; }
        .panel__header { padding: 1.5rem 1.5rem 0; }
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); }
        .panel__content { padding: 1.5rem; }

        /* Custom FullCalendar Styles */
        #calendar {
            --fc-border-color: var(--border-color);
            --fc-today-bg-color: #f3f4f6;
            --fc-button-bg-color: var(--primary-color);
            --fc-button-border-color: var(--primary-color);
            --fc-button-hover-bg-color: #4338ca;
            --fc-button-hover-border-color: #4338ca;
        }
        .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; }

        .assignment-list { list-style: none; padding: 0; margin: 0; }
        .assignment-item { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1.25rem 0; }
        .assignment-item:not(:last-child) { border-bottom: 1px solid var(--border-color); }
        .item-info { display: flex; align-items: center; gap: 1rem; }
        .item-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background-color: #eef2ff; color: var(--primary-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .item-name { font-weight: 600; font-size: 1rem; color: var(--text-primary); }
        .item-details { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }
        .history-item { opacity: 0.8; }
        .history-item .item-icon { background-color: #f3f4f6; color: var(--success-color); }
        .history-item .item-name { font-weight: 500; }

        .empty-state { text-align: center; padding: 2rem; color: var(--text-secondary); }
        .empty-state svg { margin: 0 auto 1rem; color: #d1d5db; }
        .empty-state-title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); }
        .empty-state-text { margin-top: 0.25rem; }

        .button { display: inline-flex; align-items: center; justify-content: center; padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .button:active { transform: scale(0.98); }
        .button--success { background-color: var(--success-color); color: white; }
        .button--success:hover { background-color: #059669; }
        .button--secondary { background-color: #f3f4f6; color: var(--text-primary); border: 1px solid var(--border-color); }
        .button--secondary:hover { background-color: #e5e7eb; }

    </style>

    <div class="container">
        <div class="schedule-grid">
            <div class="main-content">
                <div class="panel">
                    <div class="panel__content">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <div class="panel">
                    <div class="panel__header">
                        <h3 class="panel__title">Upcoming Jobs</h3>
                    </div>
                    <div class="panel__content">
                        <ul class="assignment-list">
                            @forelse ($upcomingAssignments as $assignment)
                                <li class="assignment-item">
                                    <div class="item-info">
                                        <div class="item-icon">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="item-name">{{ $assignment->property->name }}</p>
                                            <p class="item-details">
                                                Scheduled for: {{ \Carbon\Carbon::parse($assignment->scheduled_date)->format('l, F jS, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if(\Carbon\Carbon::parse($assignment->scheduled_date)->isToday())
                                            <a href="{{ route('housekeeper.checklist.show', $assignment) }}" class="button button--success">
                                                Start
                                            </a>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li>
                                    <div class="empty-state">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <h3 class="empty-state-title">No Upcoming Jobs</h3>
                                        <p class="empty-state-text">You have no jobs scheduled.</p>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel__header">
                        <h3 class="panel__title">Job History</h3>
                    </div>
                    <div class="panel__content">
                        <ul class="assignment-list">
                            @forelse ($completedAssignments as $assignment)
                                <li class="assignment-item history-item">
                                    <div class="item-info">
                                        <div class="item-icon">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="item-name">{{ $assignment->property->name }}</p>
                                            <p class="item-details">
                                                Completed on: {{ $assignment->checklist?->end_time?->format('M d, Y, h:i A') ?? 'Date not recorded' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>

                                    </div>
                                </li>
                            @empty
                                <li>
                                    <div class="empty-state">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                        <h3 class="empty-state-title">No Job History</h3>
                                        <p class="empty-state-text">You have not completed any jobs yet.</p>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FullCalendar JS --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/main.min.js'></script>

    {{-- This script block is unchanged as per your instructions --}}
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
