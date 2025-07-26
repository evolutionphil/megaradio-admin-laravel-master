<x-select {{ $attributes->merge([]) }}>
    <option value=""> -- Select Codec -- </option>
    @foreach($codecs as $codec)
        <option
            {{ $isSelected($codec) ? 'selected="selected"' : '' }} value="{{ $codec }}">{{ $codec }}</option>
    @endforeach
</x-select>
