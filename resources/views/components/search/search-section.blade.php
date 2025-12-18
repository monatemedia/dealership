{{-- resources/views/components/search/search-section.blade.php --}}
@props(['sections', 'value' => null])

<div class="select-container w-full">
    <select
        name="section_id"
        class="select-input"
        {{-- Captures x-model="selectedSection" from parent --}}
        {{ $attributes->whereStartsWith('x-') }}
        {{-- 🔑 DIAGNOSTIC: Log dispatch and send the event --}}
        @change="
            const id = $event.target.value;
            console.log('Section DISPATCHED:', id);
            $dispatch('section-selected', { id: id })
        "
    >
        <option value="">Section</option>
        @foreach ($sections as $category)
            <option
                value="{{ $category->id }}"
                @selected($value == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
