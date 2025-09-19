{{-- resources/views/components/hero/heading.blade.php --}}

@props(['level' => 'h1', 'class' => 'hero-slider-title'])

<{{ $level }} class="{{ $class }}">
    {{ $slot }}
</{{ $level }}>
