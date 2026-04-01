{{-- Responsive table wrapper with scroll hint --}}
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
    <div class="relative">
        {{-- Scroll hint gradient (right edge) --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-white dark:from-slate-800 to-transparent sm:hidden z-10"></div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                {{ $slot }}
            </table>
        </div>
    </div>

    @if(isset($pagination))
        <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-slate-200 dark:border-slate-700 sm:px-6">
            {{ $pagination }}
        </div>
    @endif
</div>
