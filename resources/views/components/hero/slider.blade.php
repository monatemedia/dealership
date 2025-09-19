{{-- resources/views/components/hero/slider.blade.php --}}

{{-- Hero Slider --}}
<section class="hero-slider">
    <div class="hero-slides">
        {{ $slot }}
        <!-- Carousel Controls -->
        <button type="button" class="hero-slide-prev">
            <svg style="width: 18px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 1 1 5l4 4" />
            </svg>
            <span class="sr-only">Previous</span>
        </button>
        <button type="button" class="hero-slide-next">
            <svg style="width: 18px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
            </svg>
            <span class="sr-only">Next</span>
        </button>
    </div>
</section>
<!-- End Hero Slider -->
