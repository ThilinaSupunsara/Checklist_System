<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container no-print">
            <div>
                <h2 class="page-header-title">Completed Checklist Details</h2>
            </div>
            <div class="page-header-actions">

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
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-bg: #fefce8;
            --warning-border: #fde047;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-container { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; }
        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-actions { display: flex; gap: 1rem; }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }
        .report-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
        @media (min-width: 1024px) {
            .report-grid { grid-template-columns: repeat(3, 1fr); }
            .main-content { grid-column: span 2 / span 2; }
            .sidebar { grid-column: span 1 / span 1; }
        }
        .main-content, .sidebar { display: flex; flex-direction: column; gap: 2rem; }

        .panel { background-color: var(--panel-bg); padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); }
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1rem; }

        /* Summary in main panel */
        .report-summary-header { border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .report-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); }
        .report-details { display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top: 0.75rem; font-size: 0.875rem; color: var(--text-secondary); }
        .detail-item { display: flex; align-items: center; gap: 0.5rem; }

        .room-panel { margin-bottom: 1.5rem; }
        .room-title { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; }
        .section-title { font-size: 1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1rem; }

        .task-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem; }
        .task-item { display: flex; align-items: flex-start; gap: 0.75rem; }
        .task-icon { color: var(--success-color); flex-shrink: 0; margin-top: 0.25rem; }
        .task-content { flex-grow: 1; }
        .task-description { color: var(--text-primary); }
        .task-note {
            margin-top: 0.5rem; padding: 0.5rem 0.75rem; border-radius: 0.375rem;
            background-color: var(--warning-bg); border-left: 4px solid var(--warning-border);
            font-size: 0.875rem; color: #713f12;
        }

        /* New Photo Grid Styling */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); /* Responsive grid columns */
            gap: 1rem;
        }
        .photo-item {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem; /* More rounded corners */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Softer shadow */
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .photo-item:hover {
            transform: translateY(-3px) scale(1.02); /* Slight lift and scale on hover */
            box-shadow: 0 6px 12px rgba(0,0,0,0.15); /* More prominent shadow on hover */
        }
        .photo-item img {
            width: 100%;
            height: 100px; /* Fixed height for consistency */
            object-fit: cover; /* Ensures images cover the area without distortion */
            display: block;
            transition: opacity 0.2s ease;
        }
        .photo-item:hover img {
            opacity: 0.9;
        }
        .photo-item::after {
            content: "👁️"; /* Eye icon for view */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            color: white;
            opacity: 0;
            transition: opacity 0.2s ease;
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 50%;
            padding: 0.5rem;
            line-height: 1;
        }
        .photo-item:hover::after {
            opacity: 1;
        }

        .inventory-table { width: 100%; border-collapse: collapse; }
        .inventory-table th, .inventory-table td { padding: 0.75rem 0.5rem; text-align: left; border-bottom: 1px solid var(--border-color); }
        .inventory-table thead th { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; }
        .inventory-table .text-center { text-align: center; }
        .quantity-discrepancy { color: var(--danger-color); font-weight: 700; }
        .quantity-ok { color: var(--success-color); font-weight: 700; }

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
            /* Hide the eye icon in print */
            .photo-item::after { display: none !important; }
        }
    </style>

    <div class="container printable-area">
        <div class="panel">
            {{-- This replaces the original header inside the white box --}}
            <div class="report-summary-header">
                <h3 class="report-title">{{ $checklist->assignment->property->name ?? 'Property Not Found' }}</h3>
                <div class="report-details">
                    <div class="detail-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <strong>Date:</strong> {{ $checklist->assignment->scheduled_date->format('M d, Y') }}
                    </div>
                     <div class="detail-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <strong>Housekeeper:</strong> {{ $checklist->assignment->housekeeper->name ?? 'N/A' }}
                    </div>
                     <div class="detail-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <strong>Completed At:</strong> {{ $checklist->end_time->format('M d, Y H:i A') }}
                    </div>
                </div>
            </div>

            {{-- Main content area for rooms, tasks, and photos --}}
            <div class="main-content" style="padding-top: 1.5rem;">
                @forelse ($itemsByRoom as $roomName => $items)
                    <div class="room-panel" style="padding:0; border: none; border-radius: 0;">
                        <h4 class="room-title">{{ $roomName }}</h4>

                        <div class="mb-6">
                            <h5 class="section-title">Tasks Completed</h5>
                            <ul class="task-list">
                                @foreach ($items as $item)
                                    <li class="task-item">
                                        <div class="task-icon"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                        <div class="task-content">
                                            <p class="task-description">{{ $item->task->description }}</p>
                                            @if($item->notes)
                                                <div class="task-note"><strong>Note:</strong> {{ $item->notes }}</div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <h5 class="section-title">Uploaded Photos</h5>
                             @php
                                // This PHP block is unchanged as per your instructions.
                                $photos = $items->pluck('photos')->flatten();
                            @endphp
                            @if($photos->isNotEmpty())
                                <div class="photo-grid">
                                    @foreach ($photos as $photo)
                                        <a href="{{ Storage::url('checklist_photos/' . $photo->file_path) }}" target="_blank" rel="noopener noreferrer" class="photo-item">
                                            <img src="{{ Storage::url('checklist_photos/' . $photo->file_path) }}" alt="Checklist photo">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No photos were uploaded for this room.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <p>No cleaning tasks were found for this assignment.</p>
                    </div>
                @endforelse

                @if($checklist->inventoryData->isNotEmpty())
                    <div class="mt-8">
                         <h4 class="room-title">Inventory Report</h4>
                         <div class="overflow-x-auto">
                            <table class="inventory-table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th class="text-center">Expected</th>
                                        <th class="text-center">Actual</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($checklist->inventoryData as $inventory)
                                        <tr>
                                            <td>{{ $inventory->inventoryItem->name_of_item }}</td>
                                            <td class="text-center">{{ $inventory->inventoryItem->expected_quantity }}</td>
                                            <td class="text-center {{ $inventory->actual_quantity < $inventory->inventoryItem->expected_quantity ? 'quantity-discrepancy' : 'quantity-ok' }}">
                                                {{ $inventory->actual_quantity }}
                                            </td>
                                            <td>{{ $inventory->notes ?: '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
