<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- Using the Inter font for a more modern look consistent with previous designs --}}
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Custom CSS for the new full-page background design --}}
        <style>
            .guest-background {
                background-color: #f3f4f6; /* A lighter, cleaner gray */
                background-image:
                    radial-gradient(circle at 25px 25px, rgba(0,0,0,0.02) 2%, transparent 0%),
                    radial-gradient(circle at 75px 75px, rgba(0,0,0,0.02) 2%, transparent 0%);
                background-size: 100px 100px;
            }
            .guest-logo-wrapper {
                margin-bottom: 2rem; /* Spacing below the logo */
                text-align: center;
            }
            .guest-logo {
                width: 80px; /* Adjust size as needed */
                height: auto;
            }
        </style>
        <style>
                        .guest-logo {
                        animation: pulseGlow 3s ease-in-out infinite;
                        }

                        @keyframes pulseGlow {
                        0%, 100% {
                            transform: scale(1);
                        }
                        50% {
                            transform: scale(1.1);
                        }
                        }
                        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 guest-background">
            {{-- Logo section: Removed component, added image --}}
            <div class="guest-logo-wrapper">
                <a href="/">
                    {{-- Replace 'path/to/your/logo.png' with the actual path to your logo image --}}
                    {{-- For example: '{{ asset('images/your-logo.png') }}' if stored in public/images --}}
                    <img src="{{ asset('images/HK.png') }}"alt="{{ config('app.name', 'Laravel') }} Logo" class="guest-logo">
                </a>
            </div>

            {{-- The $slot will contain the actual login/registration form --}}
            {{ $slot }}
        </div>
    </body>
</html>
