{{-- resources/views/components/sortable-vehicle-images.blade.php --}}

<div class="sortable-list-wrapper"
     data-mode="{{ $mode }}"
     @if($vehicle) data-vehicle-id="{{ $vehicle->id }}" @endif
     data-images='@json($images)'>

    @if($mode === 'normal')
        {{-- Normal mode: sync to backend --}}
        <form id="syncImagesForm"
              method="POST"
              action="{{ route('vehicle.syncImages', $vehicle) }}"
              enctype="multipart/form-data">
            @csrf
            @include('components.partials._sortable-list-inner')
        </form>
    @else
        {{-- Modal mode: integrate into parent form --}}
        <div id="modalImagesForm">
            @include('components.partials._sortable-list-inner')
        </div>
    @endif
</div>
