@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-black dark:text-white']) }}>
    {{ $value ?? $slot }}
</label>
