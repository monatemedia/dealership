<select id="citySelect" name="city_id">
    <option value="" style="display: block">City</option> {{-- default value --}}
    @foreach ($cities as $city) {{-- iterate over cities --}}
        <option
            value="{{ $city->id }}"{{-- get city id --}}
            data-parent="{{ $city->province_id }}" {{-- only show if cities is selected province --}}
            >
            {{ $city->name }} {{-- city name --}}
        </option>
    @endforeach
</select>
