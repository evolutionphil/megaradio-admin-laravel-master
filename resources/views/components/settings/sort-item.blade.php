<li {{ $attributes->merge(['class' => 'flex gap-4 justify-between items-center']) }}>
    <span x-sort:handle class="cursor-grab">
        <svg
            @click.stop
            @dragover.stop.prevent
            role="img"
            class="block w-6 text-indigo-500"
            viewBox="0 0 20 20"
            fill="currentColor">
            <path
                fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"/>
        </svg>
    </span>

    <div class="flex-1">
        {{ $slot }}
    </div>
</li>
