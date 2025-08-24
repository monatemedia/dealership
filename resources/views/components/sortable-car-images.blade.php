{{-- resources/views/components/sortable-car-images.blade.php --}}

<div class="sortable-list-wrapper"
     data-car-id="{{ $car->id }}"
     data-images='@json($images)'>

    <form id="syncImagesForm"
          method="POST"
          action="{{ route('car.syncImages', $car) }}"
          enctype="multipart/form-data">
        @csrf

        {{-- File input / Add new images --}}
        <label class="add-images">
            <input
                type="file"
                id="fileInput"
                name="images[]"
                multiple accept=".jpg,.jpeg,.png"
                hidden
            >
            <i class="fa-solid fa-plus"></i>
            <strong>Add Images</strong>
            <p style="font-size:12px;color:#64748b;">Click to select images from your device</p>
        </label>

        <p class="subtitle">Drag to reorder • Click trash to mark for deletion</p>

        {{-- Container for sortable items --}}
        <div id="list"></div>

        {{-- Hidden input for JSON payload (delete_images + positions) --}}
        <input type="hidden" name="payload" id="payloadInput">

        <div class="submit-section">
            <div>
                <h3>Ready to Submit</h3>
                <p id="markedCount">No issues</p>
            </div>
            <button type="button" id="submitBtn">
                <i class="fa-solid fa-paper-plane"></i> Submit
            </button>
        </div>
    </form>
</div>
