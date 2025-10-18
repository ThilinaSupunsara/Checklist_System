<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <div>
                <h2 class="page-header-title">{{ __('Manage My Housekeepers') }}</h2>
                <p class="page-header-subtitle">Invite new housekeepers and manage your current team.</p>
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

        .page-header-container { display: flex; justify-content: space-between; align-items: center; }
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
        .panel__title { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem; }
        .panel__description { font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1.5rem; }

        /* Form styling */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); }
        .form-input {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color);
            border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus {
            outline: none; border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); background-color: white;
        }
        .form-error-message { color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem; }
        .form-input.is-invalid { border-color: var(--danger-color); }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }

        /* Housekeeper List */
        .item-list { list-style: none; padding: 0; margin: 0; }
        .item-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
        .item-row:not(:last-child) { border-bottom: 1px solid var(--border-color); }
        .item-info { display: flex; align-items: center; gap: 1rem; }
        .item-avatar {
            width: 2.5rem; height: 2.5rem; border-radius: 50%;
            background-color: #eef2ff; color: var(--primary-color);
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; text-transform: uppercase;
        }
        .item-name { font-weight: 500; color: var(--text-primary); }
        .item-email { font-size: 0.875rem; color: var(--text-secondary); }

        .button { display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 0.75rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }
        .button--danger { background-color: var(--danger-color); color: white; }
        .button--danger:hover { background-color: #dc2626; }
        .button--secondary { background-color: #6b7280; color: white; }
        .button--secondary:hover { background-color: #4b5563; }
        .button--icon { background: none; border: none; cursor: pointer; padding: 0.5rem; color: var(--text-secondary); border-radius: 50%; transition: all 0.2s ease; }
        .button--icon:hover { color: var(--danger-color); background-color: #fee2e2; }

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
                <div class="main-content">
                    <div class="panel">
                        <h3 class="panel__title">My Current Housekeepers</h3>
                        <p class="panel__description">This list shows all housekeepers you have created.</p>

                        <ul class="item-list">
                            @forelse ($housekeepers as $housekeeper)
                                <li class="item-row">
                                    <div class="item-info">
                                        <div class="item-avatar">{{ collect(explode(' ', $housekeeper->name))->map(fn($n) => $n[0])->take(2)->implode('') }}</div>
                                        <div>
                                            <p class="item-name">{{ $housekeeper->name }}</p>
                                            <p class="item-email">{{ $housekeeper->email }}</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="openModal('{{ route('owner.my-housekeepers.destroy', $housekeeper) }}')" class="button--icon" aria-label="Remove housekeeper">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </li>
                            @empty
                                <li class="text-center text-gray-500 py-8">You have not created any housekeepers yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="sidebar">
                    <div class="panel">
                        <h3 class="panel__title">Invite New Housekeeper</h3>
                        <p class="panel__description">This creates a new user account for the housekeeper.</p>

                        <form method="POST" action="{{ route('owner.my-housekeepers.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" class="form-input @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus />
                                @error('name')<p class="form-error-message">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" class="form-input @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required />
                                @error('email')<p class="form-error-message">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" class="form-input @error('password') is-invalid @enderror" type="password" name="password" required />
                                @error('password')<p class="form-error-message">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required />
                            </div>
                            <div class="mt-8">
                                <button type="submit" class="button button--primary">{{ __('Send Invite') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showConfirmModal" x-transition class="modal-backdrop" style="display: none;">
            <div @click.away="showConfirmModal = false" x-show="showConfirmModal" x-transition class="modal-content">
                <div class="modal-icon">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="modal-title">Remove Housekeeper?</h3>
                <p class="modal-text">This will remove them from all future assignments. Are you sure?</p>
                <div class="modal-actions">
                    <button type="button" @click="showConfirmModal = false" class="button button--secondary">Cancel</button>
                    <form :action="deleteUrl" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="button button--danger">Confirm Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
