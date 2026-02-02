@if (session()->has('message'))
<div 
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 5000)"
    x-show="show"
    class="fixed top-4 right-4 z-50"
>
    <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>

        <span>{{ session('message') }}</span>

        <button @click="show = false" class="font-bold">
            ✕
        </button>
    </div>
</div>
@endif


@if (session()->has('error'))
<div 
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 6000)"
    x-show="show"
    class="fixed top-4 right-4 z-50"
>
    <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>

        <span>{{ session('error') }}</span>

        <button @click="show = false" class="font-bold">
            ✕
        </button>
    </div>
</div>
@endif
