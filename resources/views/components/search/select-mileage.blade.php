{{-- resources/views/components/search/select-mileage.blade.php --}}
@php // Open a php directive
    $options = [ // Define an array of options
        '5 000',
        '10 000',
        '25 000',
        '50 000',
        '100 000',
        '200 000',
        '300 000',
        '400 000',
        '500 000',
        '600 000',
        '700 000',
        '800 000',
        '900 000',
        '1 000 000',
        '1 500 000',
        '2 000 000',
        '2 500 000',
        '3 000 000',
        '3 500 000',
        '4 000 000',
        '4 500 000',
        '5 000 000',
    ]
@endphp

<select name="mileage">
    <option value="">Any Mileage</option>
    {{-- Iterate over array --}}
    @foreach ($options as $option)
        <option
            value="{{ $option }}"
            {{-- selected directive to remember option --}}
            @selected($attributes->get('value') == $option)>
            {{-- number_format to format with comma values--}}
            {{ number_format($option) }} or less
        </option>
    @endforeach
</select>
