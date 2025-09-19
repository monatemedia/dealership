{{-- resources/views/components/hero/image.blade.php --}}

@props(['src', 'alt' => ''])

<img src="{{ $src }}" alt="{{ $alt }}" class="img-responsive" />
