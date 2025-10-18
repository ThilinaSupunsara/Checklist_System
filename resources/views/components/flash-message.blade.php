@props([])

{{-- SUCCESS MESSAGE --}}
@if (session('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 1000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-3 scale-95"
        class="fixed bottom-6 right-6 z-50"
        role="alert"
    >
        <div class="flex items-center bg-white border border-green-300 rounded-2xl shadow-lg px-6 py-4 space-x-3">
            {{-- Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>

            {{-- Message --}}
            <p class="text-green-600 font-semibold text-sm">{{ session('success') }}</p>

            {{-- Close Button --}}
            <button
                @click="show = false"
                class="ml-4 text-green-400 hover:text-green-600 text-lg font-bold focus:outline-none"
            >
                &times;
            </button>
        </div>
    </div>
@endif

{{-- ERROR MESSAGE --}}
@if (session('error'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 1000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-3 scale-95"
        class="fixed bottom-6 right-6 z-50"
        role="alert"
    >
        <div class="flex items-center bg-white border border-red-300 rounded-2xl shadow-lg px-6 py-4 space-x-3">
            {{-- Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M12 4a8 8 0 100 16 8 8 0 000-16z"/>
            </svg>

            {{-- Message --}}
            <p class="text-red-600 font-semibold text-sm">{{ session('error') }}</p>

            {{-- Close Button --}}
            <button
                @click="show = false"
                class="ml-4 text-red-400 hover:text-red-600 text-lg font-bold focus:outline-none"
            >
                &times;
            </button>
        </div>
    </div>
@endif
