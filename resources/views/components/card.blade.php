@props(['color', 'bgColor'])


<div {{ $attributes->merge(['lang' => 'af'])->class("card card-text-$color card-bg-$bgColor") }}>
    <div {{ $title->attributes->class('card-header') }}>
        {{ $title }}
    </div>
    @if ($slot->isEmpty())
        <p>Please provide some content</p>
    @else
        {{ $slot }}
    @endif
    <div class="card-footer">{{ $footer }}</div>
</div>