<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <h2 class="page-header-title">
                Edit User: {{ $user->name }}
            </h2>
            <p class="page-header-subtitle">
                Update the user's details and role below.
            </p>
        </div>
    </x-slot>

    {{-- Custom CSS for the new form design --}}
    <style>
        :root {
            --panel-bg: #ffffff;
            --page-bg: #f8f9fa;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #d1d5db;
            --border-color-focus: #4f46e5;
            --primary-color: #4f46e5;
            --danger-color: #ef4444;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        .page-header-title { font-size: 1.5rem; font-weight: 600; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem; }

        .form-container { padding: 2rem 1.5rem; max-width: 48rem; margin: auto; }
        .panel { background-color: var(--panel-bg); padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px var(--shadow-color); }

        .form-section-divider {
            margin: 2rem 0;
            border: 0;
            border-top: 1px solid #e5e7eb;
        }

        .form-group { margin-bottom: 1.5rem; }

        .form-label {
            display: block; margin-bottom: 0.5rem; font-size: 0.875rem;
            font-weight: 500; color: var(--text-primary);
        }
        .form-label .optional { font-weight: 400; color: var(--text-secondary); }

        .form-input, .form-select {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color);
            border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus, .form-select:focus {
            outline: none; border-color: var(--border-color-focus);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); background-color: white;
        }

        .form-error-message {
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;
        }
        .form-input.is-invalid, .form-select.is-invalid { border-color: var(--danger-color); }
        .form-input.is-invalid:focus, .form-select.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }

        .form-actions {
            display: flex; justify-content: flex-end; align-items: center; gap: 1rem;
            margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);
        }

        .button {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.6rem 1.25rem; border: none; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem; text-decoration: none;
            cursor: pointer; transition: all 0.2s ease;
        }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }
        .button--secondary { background-color: transparent; color: var(--text-secondary); }
        .button--secondary:hover { background-color: #f3f4f6; }

    </style>

    <div class="form-container">
        <div class="panel">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" class="form-input @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                        @error('name')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" class="form-input @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        @error('email')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="role" class="form-label">{{ __('Role') }}</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="owner" @selected(old('role', $user->role) == 'owner')>Owner</option>
                            <option value="housekeeper" @selected(old('role', $user->role) == 'housekeeper')>Housekeeper</option>
                            <option value="admin" @selected(old('role', $user->role) == 'admin')>Admin</option>
                        </select>
                        @error('role')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>
                </div>

                <hr class="form-section-divider">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('New Password') }} <span class="optional">(Optional)</span></label>
                        <input id="password" class="form-input @error('password') is-invalid @enderror" type="password" name="password" />
                        @error('password')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                        <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" />
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="button button--secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="button button--primary">
                        {{ __('Update User') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
