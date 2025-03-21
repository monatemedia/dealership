@php
    // Declare Current Year
    $year = date('Y');
@endphp

<select name="year">
    {{-- Default Option --}}
    <option value="">Year</option>
    {{-- iterate from the given $year down to 1970, decrementing by 1 each time  --}}
    @for ($i = $year; $i >= 1970; $i--)
        <option value="{{ $i }}">
            {{ $i }}
        </option>
    @endfor
</select>
