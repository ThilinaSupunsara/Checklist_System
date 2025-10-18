<x-app-layout>
    <x-slot name="header">
        <div class="page-header-container">
            <h2 class="page-header-title">
                {{ __('Generate Report') }}
            </h2>
            <p class="page-header-subtitle">
                Select your criteria below to generate a new report.
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

        .form-input, .form-select {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color);
            border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus, .form-select:focus {
            outline: none; border-color: var(--border-color-focus);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); background-color: white;
        }

        .form-actions {
            display: flex; justify-content: flex-end; align-items: center;
            margin-top: 2rem;
        }

        .button {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem; text-decoration: none;
            cursor: pointer; transition: all 0.2s ease;
        }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); color: white; }
        .button--primary:hover { background-color: #4338ca; }

    </style>

    <div class="form-container">
        <div class="panel">
            <form action="{{ route('reports.generate') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                        <input id="start_date" class="form-input" type="date" name="start_date" required />
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                        <input id="end_date" class="form-input" type="date" name="end_date" required />
                    </div>
                </div>

                {{-- The filter section is only shown for Admin or Owner roles --}}
                @if(in_array(Auth::user()->role, ['admin', 'owner']))
                    <hr class="form-section-divider">

                    <div class="form-grid">
                        @if(Auth::user()->role === 'admin')
                            <div class="form-group">
                                <label for="property_id" class="form-label">{{ __('Filter by Property') }} <span class="optional">(Optional)</span></label>
                                <select name="property_id" id="property_id" class="form-select">
                                    <option value="">All Properties</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="housekeeper_id" class="form-label">{{ __('Filter by Housekeeper') }} <span class="optional">(Optional)</span></label>
                                <select name="housekeeper_id" id="housekeeper_id" class="form-select">
                                    <option value="">All Housekeepers</option>
                                    @foreach($housekeepers as $housekeeper)
                                        <option value="{{ $housekeeper->id }}">{{ $housekeeper->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif(Auth::user()->role === 'owner')
                            <div class="form-group form-group--full-width">
                                <label for="property_id" class="form-label">{{ __('Filter by My Property') }} <span class="optional">(Optional)</span></label>
                                <select name="property_id" id="property_id" class="form-select">
                                    <option value="">All My Properties</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="form-actions">
                    <button type="submit" class="button button--primary">
                        {{ __('Generate Report') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
