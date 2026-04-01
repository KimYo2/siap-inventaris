@props([
    'icon' => null,
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionRoute' => null,
])

<div class="flex flex-col items-center justify-center py-16 px-6 text-center w-full">
    {{-- Icon --}}
    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mb-5">
        @if($icon)
            <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}" />
            </svg>
        @else
            <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        @endif
    </div>

    {{-- Title --}}
    <h3 class="text-base font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ $title }}</h3>

    {{-- Description --}}
    @if($description)
        <p class="text-sm text-slate-400 dark:text-slate-500 max-w-sm">{{ $description }}</p>
    @endif

    {{-- Action Button --}}
    @if($actionLabel && $actionRoute)
        <a href="{{ $actionRoute }}"
            class="mt-6 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ $actionLabel }}
        </a>
    @endif
</div>
