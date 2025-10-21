{{-- resources/views/components/select-year.blade.php --}}

@php
    // Declare Current Year
    $year = date('Y');
@endphp

<select name="year">
    {{-- Default Option --}}
    <option value="">Year</option>
    {{-- iterate from the given $year down to 1970, decrementing by 1 each time  --}}
    @for ($i = $year; $i >= 1885; $i--)
        <option value="{{ $i }}" {{-- set the value of the option to the current year --}}
            {{-- Check if the current year is selected --}}
            {{-- @selected() is a Blade directive that checks if the given condition is true --}}
            {{-- It will add the selected attribute to the option if true --}}
            {{-- $attributes->get('value') is used to get the value passed to the component --}}
            {{-- $i is the current year in the loop --}}
            {{-- @selected() will add the selected attribute if the condition is true --}}
        @selected($attributes->get('value') == $i)>
            {{-- The value of the option is set to the current year --}}
            {{ $i }}
        </option>
    @endfor
</select>
