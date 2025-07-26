<select {{ $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full']) }}>
    @foreach($countries  as $country)
        <option {{ $isSelected($country->name) ? 'selected="selected"' : '' }} value="{{ $country->name }}">{{ $country->name }}</option>
    @endforeach
</select>
