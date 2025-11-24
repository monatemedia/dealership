@php // Open a php directive
    $options = [ // Define an array of options
        '10000',
        '20000',
        '30000',
        '40000',
        '50000',
        '60000',
        '70000',
        '80000',
        '90000',
        '100000',
        '150000',
        '200000',
        '250000',
        '300000',
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
