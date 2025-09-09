{{-- resources/views/components/select-vehicle-category.blade.php --}}

<select id="vehicleCategorySelect" name="vehicle_category_id">
    <option value="">Vehicle Category</option>
    @foreach($vehicleCategories as $category)
        <option value="{{ $category->id }}" @selected($attributes->get('value') == $category->id)>
            {{ $category->name }}
        </option>
    @endforeach
</select>
