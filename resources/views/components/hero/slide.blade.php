{{-- resources/views/components/hero/slide.blade.php --}}

<div class="hero-slide">
    <div class="container">
        <div class="slide-content">
            {{ $slot }}
        </div>
        <div class="slide-image">
            {{ $image }}
        </div>
    </div>
</div>
