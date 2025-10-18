<x-app-layout>

<x-slot name="header">
        <div class="page-header-container">

            <p class="page-header-subtitle">
                Welcome back, {{ Auth::user()->name }}!
            </p>
        </div>
    </x-slot>
    {{-- Custom CSS for the new dashboard design --}}
    <style>
        :root {
            --dash-bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --primary-color: #4f46e5;
            --border-color: #e5e7eb;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .dashboard-header-title {
            font-size: 1.5rem; font-weight: 600; color: var(--text-primary); line-height: 1.2;
        }

        .dashboard-container {
            padding: 2rem 1.5rem; max-width: 80rem; margin: auto;
        }

        /* NEW: Two-column layout grid */
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

        /* Main content and sidebar styling */
        .main-content, .sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* Stat cards grid (no changes) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        .stat-card {
            background-color: var(--card-bg); padding: 1.5rem; border-radius: 1rem;
            box-shadow: 0 4px 6px -1px var(--shadow-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px var(--shadow-color); }
        .stat-card__header { display: flex; justify-content: space-between; align-items: flex-start; }
        .stat-card__title { font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-card__value { font-size: 2.25rem; font-weight: 700; color: var(--text-primary); margin-top: 0.5rem; }
        .stat-card__icon { width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .stat-card__icon svg { width: 1.5rem; height: 1.5rem; }
        .icon-bg-primary { background-color: #e0e7ff; color: #4f46e5; }
        .icon-bg-blue { background-color: #dbeafe; color: #3b82f6; }
        .icon-bg-green { background-color: #d1fae5; color: #10b981; }
        .icon-bg-amber { background-color: #fef3c7; color: #f59e0b; }

        /* Generic panel styling */
        .panel {
            background-color: var(--card-bg); padding: 1.5rem; border-radius: 1rem;
            box-shadow: 0 4px 6px -1px var(--shadow-color);
        }
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.5rem; }

        /* NEW: Chart Panel */
        .chart-container { display: flex; align-items: flex-end; height: 250px; gap: 1rem; border-left: 2px solid var(--border-color); border-bottom: 2px solid var(--border-color); padding-left: 1rem; }
        .chart-bar { flex-grow: 1; background-color: #c7d2fe; border-radius: 4px 4px 0 0; transition: background-color 0.3s ease; }
        .chart-bar:hover { background-color: #818cf8; }

        /* Action buttons (no changes) */
        .actions-container { display: flex; flex-direction: column; gap: 1rem; }
        .action-button { display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1.25rem; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #ffffff; text-decoration: none; transition: background-color 0.3s ease; }
        .action-button svg { width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; }
        .button-primary { background-color: var(--primary-color); }
        .button-primary:hover { background-color: #4338ca; }
        .button-secondary { background-color: #374151; }
        .button-secondary:hover { background-color: #1f2937; }

        /* NEW: Activity Feed */
        .activity-feed { list-style: none; padding: 0; margin: 0; }
        .activity-item { display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; }
        .activity-item:not(:last-child) { border-bottom: 1px solid var(--border-color); }
        .activity-avatar { width: 2.5rem; height: 2.5rem; border-radius: 50%; background-color: #e0e7ff; color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-weight: 600; }
        .activity-content p { margin: 0; }
        .activity-name { font-weight: 500; color: var(--text-primary); }
        .activity-details { font-size: 0.875rem; color: var(--text-secondary); }

    </style>

    <div class="dashboard-container">
        <div class="dashboard-grid">
            <div class="main-content">
                <div class="stats-grid">
                    <div class="stat-card"><div><h3 class="stat-card__title">Total Users</h3><p class="stat-card__value">{{ $stats['totalUsers'] }}</p></div><div class="stat-card__icon icon-bg-primary"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m9 5.197A6 6 0 0021 15v-1h-6v1z"></path></svg></div></div>
                    <div class="stat-card"><div><h3 class="stat-card__title">Total Properties</h3><p class="stat-card__value">{{ $stats['totalProperties'] }}</p></div><div class="stat-card__icon icon-bg-blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m5-4h1m-1 4h1m-1-8h1m-5 8h1m-1-4h1"></path></svg></div></div>
                    <div class="stat-card"><div><h3 class="stat-card__title">Cleanings This Month</h3><p class="stat-card__value">{{ $stats['cleaningsThisMonth'] }}</p></div><div class="stat-card__icon icon-bg-green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div></div>
                    <div class="stat-card"><div><h3 class="stat-card__title">Pending Assignments</h3><p class="stat-card__value">{{ $stats['pendingAssignments'] }}</p></div><div class="stat-card__icon icon-bg-amber"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div></div>
                </div>


            </div>

            <div class="sidebar">
                <div class="panel">
                    <h3 class="panel__title">Quick Actions</h3>
                    <div class="actions-container">
                        <a href="{{ route('admin.users.index') }}" class="action-button button-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7a2 2 0 01-2-2V6a2 2 0 012-2h3.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293h3.172a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H20a2 2 0 012 2v2a2 2 0 01-2 2h-2"></path></svg>
                            Manage Users
                        </a>
                        <a href="{{ route('admin.defaults.index') }}" class="action-button button-secondary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Manage Templates
                        </a>
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
