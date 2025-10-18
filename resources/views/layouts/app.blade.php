<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.js"></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #4F46E5; /* Indigo */
            --secondary-color: #10B981; /* Emerald */
            --text-color-dark: #1F2937; /* Gray 800 */
            --text-color-light: #6B7280; /* Gray 500 */
            --background-color: #F9FAFB; /* Gray 50 */
            --surface-color: #FFFFFF;
            --border-color: #E5E7EB; /* Gray 200 */
            --danger-color: #EF4444; /* Red 500 */
            --success-color: #22C55E; /* Green 500 */
            --warning-color: #F59E0B; /* Amber 500 */
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color-dark);
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease;
        }

        #main-content {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Styling */
        header h2, .font-semibold.text-xl {
            color: var(--text-color-dark);
            font-weight: 600 !important;
        }

        /* Custom Flash Message Styling */
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateX(120%);
            animation: slideIn 0.5s forwards;
        }

        @keyframes slideIn {
            to { transform: translateX(0); }
        }

        .flash-message.success { background-color: var(--success-color); }
        .flash-message.error { background-color: var(--danger-color); }
        .flash-message.warning { background-color: var(--warning-color); }

        /* Scroll to Top Button */
        #scrollToTopBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        #scrollToTopBtn:hover {
            background-color: #4338CA; /* Darker Indigo */
            transform: scale(1.1);
        }
    </style>
</head>

<body class="font-sans antialiased">
    <x-flash-message />

    <div id="app-wrapper" class="min-h-screen bg-gray-50">
        @include('layouts.navigation')

        @isset($header)
        <header class="bg-white shadow-sm" role="banner">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main id="main-content" role="main">
            {{ $slot }}
        </main>
    </div>

    <button id="scrollToTopBtn" title="Go to top">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
        </svg>
    </button>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/main.min.js' defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Scroll to Top Button Logic
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');

            if (scrollToTopBtn) {
                window.onscroll = function() {
                    // Show button if user scrolls down 300px
                    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                        if (scrollToTopBtn.style.display !== 'flex') {
                            scrollToTopBtn.style.display = 'flex';
                            setTimeout(() => {
                                scrollToTopBtn.style.opacity = '1';
                            }, 10);
                        }
                    } else {
                        if (scrollToTopBtn.style.opacity === '1') {
                            scrollToTopBtn.style.opacity = '0';
                            setTimeout(() => {
                                scrollToTopBtn.style.display = 'none';
                            }, 300);
                        }
                    }
                };

                scrollToTopBtn.onclick = function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                };
            }

            // 2. Auto-hide Flash Message Logic
            const flashMessage = document.querySelector('.flash-message');

            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    flashMessage.style.opacity = '0';
                    flashMessage.style.transform = 'translateX(120%)';
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500);
                }, 5000); // 5 seconds
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
