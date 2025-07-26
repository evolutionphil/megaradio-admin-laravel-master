@props([
    'whitelist',
    'value' => [],
    'settings' => '{}'
])


@php
$name = uniqid();
$values = is_array($value) ? implode(',', $value) : $value;
@endphp

<div
    x-data="{ tagify: null }"
    class="w-full"
    x-init="tagify = initTagify($refs['{{ $name }}'], {!! $settings !!})">

    <x-input
        x-cloak
        value="{{ $values }}"
        name="{{ $attributes->get('name') }}"
        type="text"
        x-ref="{{ $name }}"
        placeholder="Add tags..."
        class="{{ $attributes->get('class') }}"></x-input>
</div>
