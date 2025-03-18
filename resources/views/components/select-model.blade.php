<select id="modelSelect" name="model_id">
    <option value="" style="display: block">Model</option> {{-- Default option --}}
    @foreach($models as $model) {{--  Iterate over models --}}
        <option
            value="{{ $model->id }}"
            data-parent="{{ $model->manufacturer_id }}"
            @selected($attributes->get('value') == $model->id)>
            {{ $model->name }}
        </option>
    @endforeach
</select>
