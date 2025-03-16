<select name="car_type_id">
    <option value="">Type</option>
    @foreach ($types as $type)
        <option
            value="{{ $type->id }}">
            {{  $type->name }}
        </option>
    @endforeach
</select>
