<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <div>
                <h2 class="page-header-title">{{ __('My Properties') }}</h2>
                <p class="page-header-subtitle">Manage all of your property listings from here.</p>
            </div>
            <a href="{{ route('owner.properties.create') }}" class="button button--primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Property
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
        .panel { background-color: var(--panel-bg); border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); overflow: hidden; }

        /* Alert styling */
        .alert {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 1rem; margin: 0 1.5rem 1.5rem; border-radius: 0.5rem;
            font-weight: 500;
        }
        .alert--success { background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }

        /* Property List */
        .property-list { list-style: none; padding: 0; margin: 0; }
        .property-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
        }
        .property-item:not(:last-child) { border-bottom: 1px solid var(--border-color); }
        .property-info { display: flex; align-items: center; gap: 1rem; }

        .property-icon {
            width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;
            background-color: #eef2ff; color: var(--primary-color);
            display: flex; align-items: center; justify-content: center;
        }

        .property-name { font-weight: 600; font-size: 1rem; color: var(--text-primary); }
        .property-details { display: flex; align-items: center; gap: 1rem; margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-secondary); }
        .property-detail-item { display: flex; align-items: center; gap: 0.5rem; }

        /* Action buttons */
        .actions-cell { display: flex; align-items: center; gap: 0.5rem; }
        .button--icon {
            display: flex; align-items: center; justify-content: center;
            width: 2.25rem; height: 2.25rem; border: none; border-radius: 50%;
            background: none; cursor: pointer; color: var(--text-secondary);
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .button--icon.manage:hover { background-color: #dbeafe; color: #2563eb; }
        .button--icon.edit:hover { background-color: #e0e7ff; color: #4338ca; }
        .button--icon.delete:hover { background-color: #fee2e2; color: #dc2626; }

        .empty-state { text-align: center; padding: 3rem; color: var(--text-secondary); }
        .empty-state svg { margin: 0 auto 1rem; }
        .empty-state-title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); }
        .empty-state-text { margin-top: 0.5rem; }
        .empty-state-action { margin-top: 1.5rem; }

        .pagination-container { padding: 1.5rem; }

        /* Button styles */
        .button { display: inline-flex; align-items: center; justify-content: center; padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
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

            <div class="panel">
                <ul class="property-list">
                    @forelse ($properties as $property)
                        <li class="property-item">
                            <div class="property-info">
                                <div class="property-icon">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m5-4h1m-1 4h1m-1-8h1m-5 8h1m-1-4h1"></path></svg>
                                </div>
                                <div>
                                    <p class="property-name">{{ $property->name }}</p>
                                    <div class="property-details">
                                        <span class="property-detail-item"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> {{ $property->beds }} Beds</span>
                                        <span class="property-detail-item"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $property->baths }} Baths</span>
                                    </div>
                                </div>
                            </div>
                            <div class="actions-cell">
                                <a href="{{ route('owner.properties.show', $property) }}" class="button--icon manage" aria-label="Manage Property">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </a>
                                <a href="{{ route('owner.properties.edit', $property) }}" class="button--icon edit" aria-label="Edit Property">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button type="button" @click="openModal('{{ route('owner.properties.destroy', $property) }}')" class="button--icon delete" aria-label="Delete Property">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </li>
                    @empty
                        <li>
                            <div class="empty-state">
                                
                                <h3 class="empty-state-title">No Properties Found</h3>
                                <p class="empty-state-text">You haven't added any properties yet. Get started by adding your first one.</p>
                                <div class="empty-state-action">
                                     <a href="{{ route('owner.properties.create') }}" class="button button--primary">
                                        Add Your First Property
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforelse
                </ul>

                @if ($properties->hasPages())
                    <div class="pagination-container">
                        {{ $properties->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div x-show="showConfirmModal" x-transition class="modal-backdrop" style="display: none;">
            <div @click.away="showConfirmModal = false" x-show="showConfirmModal" x-transition class="modal-content">
                <div class="modal-icon">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="modal-title">Delete Property?</h3>
                <p class="modal-text">Are you sure? This will delete the property and all its associated data permanently.</p>
                <div class="modal-actions">
                    <button type="button" @click="showConfirmModal = false" class="button button--secondary">Cancel</button>
                    <form :action="deleteUrl" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="button button--danger">Delete Property</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
