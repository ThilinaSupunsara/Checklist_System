<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <h2 class="page-header-title">
                Edit Property
            </h2>
            <p class="page-header-subtitle">
                Update the details for "{{ $property->name }}".
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) { .form-grid { grid-template-columns: repeat(2, 1fr); } }

        .form-group { margin-bottom: 0; }
        .form-group--full-width { grid-column: 1 / -1; }

        .form-label {
            display: block; margin-bottom: 0.5rem; font-size: 0.875rem;
            font-weight: 500; color: var(--text-primary);
        }
        .form-label .optional { font-weight: 400; color: var(--text-secondary); }

        .form-input {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color);
            border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus {
            outline: none; border-color: var(--border-color-focus);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); background-color: white;
        }

        .form-error-message {
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;
        }
        .form-input.is-invalid { border-color: var(--danger-color); }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }

        .form-actions {
            display: flex; justify-content: flex-end; align-items: center; gap: 1rem;
            margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;
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
            <form method="POST" action="{{ route('owner.properties.update', $property) }}">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <!-- Property Name -->
                    <div class="form-group form-group--full-width">
                        <label for="name" class="form-label">{{ __('Property Name') }}</label>
                        <input id="name" class="form-input @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $property->name) }}" required autofocus />
                        @error('name')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>

                    <!-- Beds -->
                    <div class="form-group">
                        <label for="beds" class="form-label">{{ __('Number of Beds') }}</label>
                        <input id="beds" class="form-input @error('beds') is-invalid @enderror" type="number" name="beds" value="{{ old('beds', $property->beds) }}" required />
                        @error('beds')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>

                    <!-- Baths -->
                    <div class="form-group">
                        <label for="baths" class="form-label">{{ __('Number of Baths') }}</label>
                        <input id="baths" class="form-input @error('baths') is-invalid @enderror" type="number" name="baths" value="{{ old('baths', $property->baths) }}" required />
                        @error('baths')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>

                    <!-- Latitude -->
                    <div class="form-group">
                        <label for="latitude" class="form-label">{{ __('Latitude') }} <span class="optional">(Optional)</span></label>
                        <input id="latitude" class="form-input @error('latitude') is-invalid @enderror" type="text" name="latitude" value="{{ old('latitude', $property->latitude) }}" placeholder="e.g., 40.7128" />
                        @error('latitude')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>

                    <!-- Longitude -->
                    <div class="form-group">
                        <label for="longitude" class="form-label">{{ __('Longitude') }} <span class="optional">(Optional)</span></label>
                        <input id="longitude" class="form-input @error('longitude') is-invalid @enderror" type="text" name="longitude" value="{{ old('longitude', $property->longitude) }}" placeholder="e.g., -74.0060" />
                        @error('longitude')<p class="form-error-message"><span>{{ $message }}</span></p>@enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('owner.properties.index') }}" class="button button--secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="button button--primary">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
