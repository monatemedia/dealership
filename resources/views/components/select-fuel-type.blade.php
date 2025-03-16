<select name="fuel_type_id">
    <option value="">Fuel Type</option>
    @foreach ($fuelTypes as $fuelType)
        <option
            value="{{ $fuelType->id }}">
            {{ $fuelType->name}}
        </option>
    @endforeach
</select>
