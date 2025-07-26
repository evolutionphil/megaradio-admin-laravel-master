<div x-data="{ isVisible: false, timeout: null }"
     x-cloak
     x-show="isVisible"
     class="pointer-events-auto relative rounded-sm border border-red-500 bg-white text-neutral-600 dark:bg-neutral-950 dark:text-neutral-300"
     x-on:pause-auto-dismiss.window="clearTimeout(timeout)"
     x-on:resume-auto-dismiss.window=" timeout = setTimeout(() => {(isVisible = false), removeNotification(notification.id) }, displayDuration)"
     x-init="$nextTick(() => { isVisible = true }), (timeout = setTimeout(() => { isVisible = false, removeNotification(notification.id)}, displayDuration))"
     x-transition:enter="transition duration-300 ease-out"
     x-transition:enter-end="translate-y-0"
     x-transition:enter-start="translate-y-8"
     x-transition:leave="transition duration-300 ease-in"
     x-transition:leave-end="-translate-x-24 opacity-0 md:translate-x-24"
     x-transition:leave-start="translate-x-0 opacity-100"
     role="status"
     aria-live="polite"
     aria-atomic="true">
    <div class="flex w-full items-center gap-2.5 bg-red-500/10 rounded-sm p-4 transition-all duration-300">

        <!-- Icon -->
        <div class="rounded-full bg-red-500/15 p-0.5 text-red-500" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" class="size-5" aria-hidden="true">
                <path fill="currentColor" d="M12 3a9 9 0 1 0 0 18a9 9 0 0 0 0-18M1 12C1 5.925 5.925 1 12 1s11 4.925 11 11s-4.925 11-11 11S1 18.075 1 12m12-5.5V14h-2V6.5zm-2 9h2.004v2.004H11z"/>
            </svg>
        </div>

        <!-- Title & Message -->
        <div class="flex flex-col gap-1">
            <h3 x-cloak
                x-show="notification.title"
                class="text-sm font-semibold text-red-600"
                x-text="notification.title"></h3>

            <p x-cloak
               x-show="notification.message"
               class="text-pretty text-sm text-red-500"
               x-text="notification.message"></p>
        </div>

        <!--Dismiss Button -->
        <button type="button"
                class="ml-auto"
                aria-label="dismiss notification"
                x-on:click="(isVisible = false), removeNotification(notification.id)">
            <svg xmlns="http://www.w3.org/2000/svg viewBox=" 0 0 24 24 stroke="currentColor" fill="none" stroke-width="2" class="size-5 shrink-0" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
