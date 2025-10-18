<x-guest-layout>
    {{-- Custom CSS for the new design --}}
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

        .login-panel {
            background-color: var(--panel-bg);
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px var(--shadow-color);
            width: 100%;
            max-width: 28rem;
        }

        .panel-header { text-align: center; margin-bottom: 2rem; }
        .panel-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); }
        .panel-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem; }

        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); }

        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem; /* Add padding for icon */
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-color: #f9fafb;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--border-color-focus);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            background-color: white;
        }

        .form-error-message { color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem; }
        .form-input.is-invalid { border-color: var(--danger-color); }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }

        .form-options { display: flex; justify-content: space-between; align-items: center; }
        .remember-me { display: flex; align-items: center; gap: 0.5rem; }
        .remember-me label { font-size: 0.875rem; color: var(--text-secondary); cursor: pointer; }
        .remember-me input { border-radius: 0.25rem; border-color: var(--border-color); }

        .forgot-password { font-size: 0.875rem; color: var(--primary-color); text-decoration: none; font-weight: 500; }
        .forgot-password:hover { text-decoration: underline; }

        .button {
            display: inline-flex; align-items: center; justify-content: center;
            width: 100%;
            padding: 0.75rem 1.25rem; border: none; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;
            color: #ffffff; text-decoration: none; cursor: pointer;
            transition: all 0.2s ease;
        }
        .button:active { transform: scale(0.98); }
        .button--primary { background-color: var(--primary-color); }
        .button--primary:hover { background-color: #4338ca; }

        .auth-session-status {
            padding: 1rem; margin-bottom: 1rem; border-radius: 0.5rem;
            background-color: #d1fae5; color: #065f46; font-size: 0.875rem;
        }

    </style>
{{-- Custom CSS for the new header design --}}
<style>
    :root {
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --primary-color: #4f46e5;
        --border-color: #e5e7eb;
    }

    .header-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem; /* Reduced gap for button group */
    }

    .button-nav {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border: 1px solid transparent;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .button-nav:active {
        transform: scale(0.98);
    }

    /* Primary action button (Register / Dashboard) */
    .button-nav--primary {
        background-color: var(--primary-color);
        color: white;
    }
    .button-nav--primary:hover {
        background-color: #4338ca;
    }

    /* Secondary action button (Log in) */
    .button-nav--secondary {
        background-color: transparent;
        color: var(--text-primary);
    }
    .button-nav--secondary:hover {
        background-color: #f3f4f6; /* Light gray background on hover */
    }
</style>




    <div class="login-panel">
        <div class="panel-header">
            <h2 class="panel-title">Sign In</h2>
            <p class="panel-subtitle">Welcome back! Please sign in to continue.</p>
        </div>

        <x-auth-session-status class="auth-session-status" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <div class="input-wrapper">
                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    <input id="email" class="form-input @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                </div>
                @error('email')<p class="form-error-message">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <div class="input-wrapper">
                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <input id="password" class="form-input @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" />
                </div>
                @error('password')<p class="form-error-message">{{ $message }}</p>@enderror
            </div>

            <div class="form-options mt-4">
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">{{ __('Remember me') }}</label>
                </div>

            </div>



            @if (Route::has('login'))
                @auth
                 <div class="mt-8"></div>
                    <a href="{{ url('/dashboard') }}" class="button button--primary">

                        <span>Dashboard</span>
                    </a>
                    </div>
                @else
                    <div class="mt-8">
                    <button type="submit" class="button button--primary">
                        {{ __('Log in') }}
                    </button>
                    </div>
                @endauth
        @endif
        </form>
    </div>
</x-guest-layout>
