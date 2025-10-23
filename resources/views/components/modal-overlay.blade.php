{{-- resources/views/components/modal-overlay.blade.php --}}
@props(['title' => '', 'maxWidth' => '800px'])

<div
    x-show="isOpen"
    x-cloak
    class="modal-backdrop"
    @click="closeModal"
    style="display: none;"
>
    <div
        class="modal-content"
        style="max-width: {{ $maxWidth }};"
        @click.stop
    >
        @if($title)
            <div class="modal-header">
                <h2>{{ $title }}</h2>
            </div>
        @endif

        <div class="modal-body">
            {{ $slot }}
        </div>
    </div>
</div>
