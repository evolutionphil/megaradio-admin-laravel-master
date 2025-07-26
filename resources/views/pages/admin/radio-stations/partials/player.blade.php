<div class="fixed bottom-0 w-full bg-gray-50 border-t z-30" x-data x-show="$store.radioPlayer.player">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 w-full items-center justify-between px-4">
            <div x-show="$store.radioPlayer.station">
                <h2 class="font-medium" x-text="$store.radioPlayer.station && $store.radioPlayer.station.name">No station selected</h2>
            </div>
            <div class="flex gap-2">
                <button x-show="!$store.radioPlayer.player" class="rounded-full border-2 p-2">
                    <svg class="h-4 fill-gray-600" fill="current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 5.274c0-1.707 1.826-2.792 3.325-1.977l12.362 6.726c1.566.853 1.566 3.101 0 3.953L8.325 20.702C6.826 21.518 5 20.432 5 18.726V5.274Z" />
                    </svg>
                </button>
                <button x-show="$store.radioPlayer.player" class="rounded-full border-2 p-2" @click="$store.radioPlayer.stop()">
                    <svg class="h-4 fill-gray-600" fill="current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.75 3A1.75 1.75 0 0 0 3 4.75v14.5c0 .966.784 1.75 1.75 1.75h14.5A1.75 1.75 0 0 0 21 19.25V4.75A1.75 1.75 0 0 0 19.25 3H4.75Z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
