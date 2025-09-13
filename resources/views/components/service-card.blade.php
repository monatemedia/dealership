<div class="service-card">
    <a href="{{ $href }}">
        <img src="{{ $image }}" alt="{{ $title }}">
        <div class="service-overlay"></div>
        <div class="service-content">
            <h3>{{ $title }}</h3>
            <p>{{ $description }}</p>
            <div class="service-link">
                Learn more
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/>
                </svg>
            </div>
        </div>
    </a>
</div>
