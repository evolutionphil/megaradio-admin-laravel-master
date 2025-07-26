@props(['disabled' => false])

<div class="w-full">
    <textarea
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => 'focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md']) !!}>{{ $slot }}</textarea>
    @if ($errors->any())
        <p class="mt-2 text-sm text-red-600">
            @error($attributes->get('name'))
            {{ $message }}
            @enderror
        </p>
    @endif
</div>
