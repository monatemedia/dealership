<select id="modelSelect" name="model_id">
    <option value="" style="display: block">Model</option> {{-- Default option --}}
    @foreach($models as $model) {{--  Iterate over models --}}
        <option
            value="{{ $model->id }}" {{-- Get Model id --}}
            data-parent="{{ $model->manufacturer_id }}"> {{--  Show only models of selected manufacturer--}}
            {{ $model->name }} {{-- Model name --}}
        </option>
    @endforeach
</select>
