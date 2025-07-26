@props([
    'title',
    'name',
    'content',
    'footer',
])

<div
    x-data="{show: false}"
    x-cloak
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-on:keydown.esc.window="show = false"
    x-on:click.self="show = false"
    class="fixed inset-0 z-30 flex items-end justify-center bg-black/20 backdrop-blur-md sm:items-center"
    role="dialog"
    aria-modal="true"
    aria-labelledby="defaultModalTitle"
>
    <!-- Modal Dialog -->
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
        x-transition:enter-start="opacity-0 scale-50"
        x-transition:enter-end="opacity-100 scale-100"
        class="min-w-[400px] flex max-w-lg flex-col gap-2 overflow-hidden rounded-lg border border-outline bg-white text-black absolute top-1/2 -translate-y-1/2">
        <!-- Dialog Header -->
        <div class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
            <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">{{ $title ?? 'New Modal' }}</h3>
            <button x-on:click="show = false" aria-label="close modal">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <!-- Dialog Body -->
        <div class="p-4 w-full">
            {{ $content }}
        </div>
    </div>
</div>
