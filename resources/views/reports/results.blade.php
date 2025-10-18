<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container no-print">
            <div>
                <h2 class="page-header-title">{{ __('Report Results') }}</h2>
                <p class="page-header-subtitle">
                    Showing results from <strong>{{ \Carbon\Carbon::parse($reportData['start_date'])->format('M d, Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($reportData['end_date'])->format('M d, Y') }}</strong>
                </p>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('reports.index') }}" class="button button--secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    New Report
                </a>
                {{-- This button is hidden when printing via CSS --}}
                <button onclick="window.print()" class="button button--primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-8V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Report
                </button>
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
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-container { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; }
        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }
        .page-header-actions { display: flex; gap: 1rem; }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }

        .panel { background-color: var(--panel-bg); border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); overflow: hidden; }

        /* Report List */
        .report-list { list-style: none; padding: 0; margin: 0; }
        .report-item { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; padding: 1rem 1.5rem; }
        .report-item:not(:last-child) { border-bottom: 1px solid var(--border-color); }
        .item-info { display: flex; align-items: center; gap: 1rem; }

        .item-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background-color: #eef2ff; color: var(--primary-color); display: flex; align-items: center; justify-content: center; }

        .item-name { font-weight: 600; font-size: 1rem; color: var(--text-primary); }
        .item-details { display: flex; flex-wrap: wrap; align-items: center; gap: 1.5rem; margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-secondary); }
        .item-detail-item { display: flex; align-items: center; gap: 0.5rem; }

        .empty-state { text-align: center; padding: 3rem; color: var(--text-secondary); }
        .empty-state svg { margin: 0 auto 1rem; }
        .empty-state-title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); }

        .button { display: inline-flex; align-items: center; justify-content: center; padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }
        .button--secondary { background-color: #f3f4f6; color: var(--text-primary); border: 1px solid var(--border-color); }
        .button--secondary:hover { background-color: #e5e7eb; }

        @media print {
            body { background-color: white; }
            .no-print, .no-print * { display: none !important; }
            .printable-area { padding: 0; }
            .panel { box-shadow: none; border: 1px solid var(--border-color); }
            x-app-layout, main { padding: 0 !important; }
        }
    </style>

    <div class="container printable-area">
        {{-- The header from the original code is integrated into the new header slot above --}}

        <div class="panel">
            <ul class="report-list">
                @forelse ($checklists as $checklist)
                    <li class="report-item">
                        <div class="item-info">
                            <div class="item-icon">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <div>
                                <p class="item-name">{{ $checklist->assignment?->property?->name ?? 'Property Deleted' }}</p>
                                <div class="item-details">
                                    <span class="item-detail-item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ $checklist->assignment?->housekeeper?->name ?? 'User Deleted' }}
                                    </span>
                                    <span class="item-detail-item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $checklist->end_time->format('M d, Y H:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="no-print">
                            @php
                                // Determine the correct route based on the user's role
                                $routeName = Auth::user()->role === 'admin' ? 'admin.checklists.show' : 'owner.checklists.show';
                            @endphp
                            <a href="{{ route($routeName, $checklist) }}" class="button button--secondary">View Details</a>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="empty-state">
                            
                            <h3 class="empty-state-title">No Results Found</h3>
                            <p>No completed checklists were found for the selected criteria.</p>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
