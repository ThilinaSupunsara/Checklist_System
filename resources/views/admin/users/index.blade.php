<x-app-layout>


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
            --success-color: #10b981;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-container { display: flex; justify-content: space-between; align-items: center; }
        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }

        .container { padding: 2rem 1.5rem; max-width: 80rem; margin: auto; }
        .panel { background-color: var(--panel-bg); border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); overflow: hidden; }

        /* Alert styling */
        .alert {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 1rem; margin: 0 1.5rem 1.5rem; border-radius: 0.5rem;
            font-weight: 500;
        }
        .alert--success { background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .alert--danger { background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* Table styling */
        .user-table-wrapper { overflow-x: auto; }
        .user-table { width: 100%; border-collapse: collapse; }
        .user-table th, .user-table td { padding: 1rem 1.5rem; text-align: left; }
        .user-table thead { background-color: #f9fafb; }
        .user-table th {
            font-size: 0.75rem; font-weight: 600; color: var(--text-secondary);
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .user-table tbody tr { border-bottom: 1px solid var(--border-color); }
        .user-table tbody tr:last-child { border-bottom: none; }

        /* User info cell with avatar */
        .user-info { display: flex; align-items: center; gap: 1rem; }
        .user-avatar {
            width: 2.5rem; height: 2.5rem; border-radius: 50%;
            background-color: #e0e7ff; color: var(--primary-color);
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; text-transform: uppercase;
        }
        .user-name { font-weight: 500; color: var(--text-primary); }
        .user-email { font-size: 0.875rem; color: var(--text-secondary); }

        /* Role badge */
        .role-badge {
            display: inline-flex; padding: 0.25rem 0.75rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
        }
        .role-admin { background-color: #d1fae5; color: #065f46; }
        .role-owner { background-color: #e0e7ff; color: #3730a3; }
        .role-housekeeper { background-color: #fef3c7; color: #92400e; }

        /* Action buttons */
        .actions-cell { display: flex; align-items: center; gap: 0.5rem; }
        .button--icon {
            display: flex; align-items: center; justify-content: center;
            width: 2.25rem; height: 2.25rem; border: none; border-radius: 50%;
            background: none; cursor: pointer; transition: background-color 0.2s ease;
        }
        .button--icon.edit:hover { background-color: #e0e7ff; color: #4338ca; }
        .button--icon.delete:hover { background-color: #fee2e2; color: #dc2626; }

        .empty-state { text-align: center; padding: 3rem; color: var(--text-secondary); }
        .pagination-container { padding: 1.5rem; }

        /* Button styles (reused from previous design) */
        .button { display: inline-flex; align-items: center; justify-content: center; padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }
        .button--danger { background-color: var(--danger-color); color: white; }
        .button--danger:hover { background-color: #dc2626; }
        .button--secondary { background-color: #6b7280; color: white; }
        .button--secondary:hover { background-color: #4b5563; }

        /* Modal styles (reused from previous design) */
        .modal-backdrop { position: fixed; inset: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
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
            @if (session('success'))
                <div class="alert alert--success" role="alert">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert--danger" role="alert">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            <div style="display: flex; justify-content: flex-end;">
            <a href="{{ route('admin.users.create') }}" class="button button--primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New User
            </a>
        </div>
            <div class="panel">
                <div class="user-table-wrapper">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                {{-- Generates initials from name --}}
                                                {{ collect(explode(' ', $user->name))->map(fn($n) => $n[0])->take(2)->implode('') }}
                                            </div>
                                            <div>
                                                <div class="user-name">{{ $user->name }}</div>
                                                <div class="user-email">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="role-badge role-{{ $user->role }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-cell justify-end">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="button--icon edit" aria-label="Edit user">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <button type="button" @click="openModal('{{ route('admin.users.destroy', $user) }}')" class="button--icon delete" aria-label="Delete user">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m9 5.197A6 6 0 0021 15v-1h-6v1z"></path></svg>
                                            <p class="mt-4">No users found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="pagination-container">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div x-show="showConfirmModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:leave="ease-in duration-200" x-transition:leave-end="opacity-0" class="modal-backdrop" style="display: none;">
            <div @click.away="showConfirmModal = false" x-show="showConfirmModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="modal-content">
                <div class="modal-icon">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="modal-title">Delete User?</h3>
                <p class="modal-text">Are you sure you want to permanently delete this user? This action cannot be undone.</p>
                <div class="modal-actions">
                    <button type="button" @click="showConfirmModal = false" class="button button--secondary">Cancel</button>
                    <form :action="deleteUrl" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button button--danger">Delete User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
