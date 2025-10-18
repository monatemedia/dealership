{{-- resources/views/components/taxonomy/card.blade.php --}}
@props([
    'href',
    'image',
    'title',
    'description',
    'selectingForCreate' => false,
    'typeLabel' => 'Category'
])

<div class="category-card">
    <a href="{{ $href }}">
        <img src="{{ $image }}" alt="{{ $title }}">
        <div class="category-overlay"></div>
        <div class="category-content">
            <h3>{{ $title }}</h3>
            <p>{{ $description }}</p>
            <div class="category-link">
                {{ $selectingForCreate ? 'Select ' . $typeLabel : 'See more' }}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/>
                </svg>
            </div>
        </div>
    </a>
</div>
