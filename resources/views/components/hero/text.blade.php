{{-- resources/views/components/hero/text.blade.php --}}

<p {{ $attributes->merge(['class' => 'hero-slider-content']) }}>
    {{ $slot }}
</p>
