<section class="panel panel--danger-zone">
    {{-- Custom CSS for the new design --}}
    <style>
        .panel {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        /* Special border for the danger zone panel */
        .panel--danger-zone {
            border: 1px solid #fee2e2; /* Light red border */
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
            max-width: 65ch; /* Improves readability */
        }
        .panel-content {
            margin-top: 1.5rem;
        }

        /* --- Input & Button Styles (reused) --- */
        .form-input {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;
            font-size: 1rem; transition: all 0.2s ease; background-color: #f9fafb;
        }
        .form-input:focus {
            outline: none; border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); background-color: white;
        }
        .form-error-message {
            display: flex; align-items: center; gap: 0.5rem; color: #ef4444;
            font-size: 0.875rem; margin-top: 0.5rem;
        }
        .form-input.is-invalid { border-color: #ef4444; }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }
        .button {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.6rem 1.25rem; border: none; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer;
            transition: all 0.2s ease;
        }
        .button:active { transform: scale(0.98); }
        .button--danger { background-color: #ef4444; color: white; }
        .button--danger:hover { background-color: #dc2626; }
        .button--secondary { background-color: #e5e7eb; color: #374151; }
        .button--secondary:hover { background-color: #d1d5db; }

        /* --- Modal Styles (reused) --- */
        .modal-backdrop { position: fixed; inset: 0; background-color: rgba(17, 24, 39, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 50; }
        .modal-content { background-color: white; border-radius: 1rem; padding: 2rem; width: 100%; max-width: 440px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .modal-header { text-align: center; }
        .modal-icon {
            width: 3rem; height: 3rem; margin: 0 auto 1rem; border-radius: 50%;
            background-color: #fee2e2; display: flex; align-items: center; justify-content: center;
            color: #ef4444;
        }
        .modal-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
        .modal-text { color: #6b7280; margin-bottom: 1.5rem; }
        .modal-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; }
    </style>

    <header>
        <h2 class="panel-header__title">
            {{ __('Delete Account') }}
        </h2>

        <p class="panel-header__description">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="panel-content">
        <button class="button button--danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Delete Account') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-icon">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h2 class="modal-title">
                        {{ __('Are you sure?') }}
                    </h2>
                    <p class="modal-text">
                        {{ __('This action is permanent. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="password" class="sr-only">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" class="form-input @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="{{ __('Password') }}" />
                    @if($errors->userDeletion->has('password'))
                        <p class="form-error-message">
                           <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                           <span>{{ $errors->userDeletion->first('password') }}</span>
                        </p>
                    @endif
                </div>

                <div class="modal-actions">
                    <button type="button" class="button button--secondary" x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="button button--danger">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
</section>
