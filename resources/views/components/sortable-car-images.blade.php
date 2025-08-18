{{-- Sortable Images Component --}}
<div class="sortable-list-wrapper">
    {{-- File input / Add images --}}
    <label class="add-images">
        <input type="file" id="fileInput" multiple accept=".jpg,.jpeg,.png" hidden>
        <i class="fa-solid fa-plus"></i>
        <strong>Add Images</strong>
        <p style="font-size:12px;color:#64748b;">Click to select images from your device</p>
    </label>

    <p class="subtitle">Drag to reorder • Click trash to mark for deletion</p>

    {{-- Container for sortable items --}}
    <div id="list"></div>

    {{-- Submit section --}}
    <div class="submit-section">
        <div>
            <h3>Ready to Submit</h3>
            <p id="markedCount"></p>
        </div>
        <button id="submitBtn">
            <i class="fa-solid fa-paper-plane"></i>Submit
        </button>
    </div>

    {{-- JS-ready images for this component --}}
    <script>
        // Pass PHP data to JS
        window.carImages = @json($images);
    </script>
</div>
