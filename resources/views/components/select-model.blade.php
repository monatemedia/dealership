<select id="modelSelect" name="model_id">
    <option value="" style="display: block">Model</option> {{-- Default option --}}
    @foreach($models as $model) {{--  Iterate over models --}}
        <option
            value="{{ $model->id }}" {{-- Get Model id --}}
            data-parent="{{ $model->manufacturer_id }}"> {{--  Get associaed model-?manufacturer_id --}}
            {{ $model->name }} {{-- Model name --}}
        </option>
    @endforeach
</select>
