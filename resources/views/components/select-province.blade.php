<select id="provinceSelect" name="province_id">
    <option value="">Province</option> {{-- default option --}}
    @foreach ($provinces as $province) {{-- iterate over provinces --}}
        <option value="{{ $province->id }}"> {{-- province id --}}
            {{ $province->name }}{{-- province name --}}
        </option>
    @endforeach
</select>
