{{-- resource/views/components/title.blade.php --}}

@props([
    'tag' => 'h2',      // default header tag
    'title' => '',      // header text
    'paragraph' => null // optional paragraph
])

<div>
    <<?= $tag ?> class="hero-slider-title text-center">
        {!! $title !!}
    </<?= $tag ?>>

    @if($paragraph)
        <p class="hero-slider-content text-center">
            {!! $paragraph !!}
        </p>
    @endif
</div>
