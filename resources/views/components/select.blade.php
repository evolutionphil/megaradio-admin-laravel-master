@props(['disabled' => false, 'label' => null])

<div>
    @if ($label)
        <label for="{{ $attributes->get('name') }}" class="block text-sm font-medium text-gray-700 dark:text-white">
            {{ $label }}
        </label>
    @endif
    <select {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md']) }}>
    {{ $slot }}
    </select>
        @if ($errors->any())
    <p class="mt-2 text-sm text-red-600">
        @error($attributes->get('name'))
            {{ $message }}
        @enderror
    </p>
    @endif

</div>
