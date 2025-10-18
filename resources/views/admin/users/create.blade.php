<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <h2 class="page-header-title">
                {{ __('Create New User') }}
            </h2>
            <p class="page-header-subtitle">
                Fill out the form below to add a new user to the system.
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

        /* Form styling */
        .form-group { margin-bottom: 1.5rem; }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--border-color-focus);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            background-color: white;
        }

        /* Smart Error Styling */
        .form-error-message {
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;
        }
        .form-input.is-invalid, .form-select.is-invalid {
            border-color: var(--danger-color);
        }
        .form-input.is-invalid:focus, .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .button {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.6rem 1.25rem; border: none; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem;
            text-decoration: none; cursor: pointer;
            transition: all 0.2s ease;
        }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }
        .button--secondary { background-color: transparent; color: var(--text-secondary); }
        .button--secondary:hover { background-color: #f3f4f6; }

    </style>

    <div class="form-container">
        <div class="panel">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input id="name" class="form-input @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus />
                    @error('name')
                        <p class="form-error-message">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" class="form-input @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required />
                    @error('email')
                        <p class="form-error-message">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label for="role" class="form-label">{{ __('Role') }}</label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="housekeeper" {{ old('role') == 'housekeeper' ? 'selected' : '' }}>Housekeeper</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                         <p class="form-error-message">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" class="form-input @error('password') is-invalid @enderror" type="password" name="password" required />
                        @error('password')
                           <p class="form-error-message">
                               <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                               <span>{{ $message }}</span>
                           </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required />
                    </div>
                </div>


                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="button button--secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="button button--primary">
                        {{ __('Create User') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
