@props(['name' => '', 'value' => false])

<button
    x-data="{ on: {{ in_array($value, ['true', true, 1, '1']) ? 'true' : 'false' }} }"
    @click="on = !on"
    type="button"
    x-state:on="Enabled"
    x-state:off="Not Enabled"
    role="switch"
    :aria-checked="on.toString()"
    :class="{ 'bg-indigo-500': on, 'bg-gray-200': !(on) }"
    {{ $attributes->merge(['class' => 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']) }}
>
    <input name="{{ $name }}" type="hidden" :value="on.toString()"
    >
    <span
        aria-hidden="true"
        x-state:on="Enabled"
        x-state:off="Not Enabled"
        :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"
        class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
    ></span>
</button>
