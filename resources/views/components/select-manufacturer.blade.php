<select id="manufacturerSelect" name="manufacturer_id">
    <option value="">Manufacturer</option> {{--  Default value --}}
    @foreach($manufacturers as $manufacturer) {{-- Iterate over manufacturers --}}
        <option
            value="{{ $manufacturer->id }}">{{-- get manufacturer id --}}
                {{ $manufacturer->name }} {{-- get manufacturer name --}}
	    </option>
    @endforeach
</select>
