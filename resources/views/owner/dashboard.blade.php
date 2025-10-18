<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">

            <p class="page-header-subtitle">
                Welcome back, {{ Auth::user()->name }}!
            </p>
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
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media (min-width: 1024px) {
            .dashboard-grid { grid-template-columns: repeat(3, 1fr); }
            .main-content { grid-column: span 2 / span 2; }
            .sidebar { grid-column: span 1 / span 1; }
        }

        .main-content, .sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .stat-card {
            background-color: var(--panel-bg); padding: 1.5rem; border-radius: 1rem;
            box-shadow: 0 4px 6px -1px var(--shadow-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px var(--shadow-color); }
        .stat-card__header { display: flex; justify-content: space-between; align-items: flex-start; }
        .stat-card__title { font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-card__value { font-size: 2.25rem; font-weight: 700; color: var(--text-primary); margin-top: 0.5rem; }
        .stat-card__icon { width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .stat-card__icon svg { width: 1.5rem; height: 1.5rem; }
        .icon-bg-blue { background-color: #dbeafe; color: #3b82f6; }
        .icon-bg-amber { background-color: #fef3c7; color: #f59e0b; }
        .icon-bg-primary { background-color: #e0e7ff; color: #4f46e5; }

        .panel {
            background-color: var(--panel-bg); padding: 1.5rem; border-radius: 1rem;
            box-shadow: 0 4px 6px -1px var(--shadow-color);
        }
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.5rem; }

        .action-button {
            display: flex; align-items: center; justify-content: center;
            width: 100%; padding: 0.75rem 1.25rem; border: none; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem; text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .action-button svg { width: 1.25rem; height: 1.25rem; margin-right: 0.75rem; }
        .button-primary { background-color: var(--primary-color); color: white; }
        .button-primary:hover { background-color: #4338ca; }
        .button-secondary { background-color: #374151; color: white; }
        .button-secondary:hover { background-color: #1f2937; }

        .item-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem; }
        .item-row { display: flex; justify-content: space-between; align-items: center; }
        .item-row__info { display: flex; align-items: center; gap: 1rem; }
        .item-row__avatar {
            width: 2.5rem; height: 2.5rem; border-radius: 50%;
            background-color: #e5e7eb; color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
        }
        .item-row__name { font-weight: 500; color: var(--text-primary); }
        .item-row__description { font-size: 0.875rem; color: var(--text-secondary); }
        .item-row__date { font-size: 0.875rem; font-weight: 500; color: var(--text-primary); }

    </style>

    <div class="container">
        <div class="dashboard-grid">
            <div class="main-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card__header">
                            <h3 class="stat-card__title">My Properties</h3>
                            <div class="stat-card__icon icon-bg-blue">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m5-4h1m-1 4h1m-1-8h1m-5 8h1m-1-4h1"></path></svg>
                            </div>
                        </div>
                        <p class="stat-card__value">{{ $stats['totalProperties'] }}</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__header">
                            <h3 class="stat-card__title">Upcoming Assignments</h3>
                            <div class="stat-card__icon icon-bg-amber">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <p class="stat-card__value">{{ $stats['upcomingAssignments'] }}</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__header">
                            <h3 class="stat-card__title">My Housekeepers</h3>
                            <div class="stat-card__icon icon-bg-primary">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m9 5.197A6 6 0 0021 15v-1h-6v1z"></path></svg>
                            </div>
                        </div>
                        <p class="stat-card__value">{{ $stats['totalHousekeepers'] }}</p>
                    </div>
                </div>

<div class="panel">
                            <h3 class="panel__title">Upcoming Assignments (Next 7 Days)</h3>
                            <ul class="item-list">

                                {{-- ## REPLACE THE OLD HARDCODED LIST WITH THIS LOOP ## --}}
                                @forelse ($upcomingAssignmentsList as $assignment)
                                    <li class="item-row">
                                        <div class="item-row__info">
                                            <div class="item-row__avatar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="item-row__name">{{ $assignment->property?->name ?? 'Property Deleted' }}</p>
                                                <p class="item-row__description">Assigned to: {{ $assignment->housekeeper?->name ?? 'User Deleted' }}</p>
                                            </div>
                                        </div>
                                        <p class="item-row__date">{{ $assignment->scheduled_date->format('M d') }}</p>
                                    </li>
                                @empty
                                    <li class="text-center text-gray-500 py-4">
                                        No assignments scheduled for the next 7 days.
                                    </li>
                                @endforelse
                                {{-- ## END OF REPLACEMENT ## --}}

                            </ul>
                        </div>
            </div>

            <div class="sidebar">
                <div class="panel">
                    <h3 class="panel__title">Quick Actions</h3>
                    <div class="flex flex-col gap-4">
                        <a href="{{ route('owner.properties.index') }}" class="action-button button-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            Manage Properties
                        </a>
                        <a href="{{ route('owner.calendar.index') }}" class="action-button button-secondary">
                             <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            View Calendar
                        </a>
                        <a href="{{ route('owner.my-housekeepers.index') }}" class="action-button button-secondary">
                             <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m9 5.197A6 6 0 0021 15v-1h-6v1z"></path></svg>
                            Manage Housekeepers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
