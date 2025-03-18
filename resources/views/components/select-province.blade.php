<select id="provinceSelect" name="province_id">
    <option value="">Province</option>
    @foreach ($provinces as $province)
        <option value="{{ $province->id }}"
            @selected($attributes->get('value') == $province->id)>
            {{ $province->name }}
        </option>
    @endforeach
</select>
