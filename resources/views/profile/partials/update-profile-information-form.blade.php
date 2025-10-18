<section class="panel">
    {{-- Custom CSS for the new design --}}
    <style>
        .panel {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .panel-header__title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }
        .panel-header__description {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .form-grid {
            margin-top: 1.5rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .form-group--span-2 {
                grid-column: span 2 / span 2;
            }
        }
        .form-group {
            margin-bottom: 0; /* Adjusted for grid layout */
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            background-color: white;
        }
        .form-error-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .form-input.is-invalid {
            border-color: #ef4444;
        }
        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        .form-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.25rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .button:active {
            transform: scale(0.98);
        }
        .button--primary {
            background-color: #4f46e5;
            color: white;
        }
        .button--primary:hover {
            background-color: #4338ca;
        }
        .status-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #10b981; /* Green */
        }
    </style>

    <header>
        <h2 class="panel-header__title">
            {{ __('Profile Information') }}
        </h2>
        <p class="panel-header__description">
            {{ __("Update your account's profile information, email address, and phone number.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="form-grid">
            <div class="form-group form-group--span-2">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" name="name" type="text" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name')
                    <p class="form-error-message">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                 @error('email')
                    <p class="form-error-message">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone_number" class="form-label">{{ __('Phone Number') }}</label>
                <input id="phone_number" name="phone_number" type="text" class="form-input @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}" autocomplete="tel" />
                 @error('phone_number')
                    <p class="form-error-message">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="button button--primary">{{ __('Save Changes') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="status-message">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ __('Saved successfully.') }}</span>
                </p>
            @endif
        </div>
    </form>
</section>
