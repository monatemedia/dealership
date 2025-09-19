{{-- resource/views/components/hero/home-slider.blade.php --}}

<x-hero.slider>
<!-- Slide 1 -->
    <x-hero.slide>

        <x-slot:image>
            <x-hero.image src="/img/car-png-39071.png" alt="Car Image" />
        </x-slot:image>

        <x-hero.heading level="h1">
            Buy <strong>{{ $category->name ?? 'The Best Vehicles' }}</strong> <br>
            in your region
        </x-hero.heading>

        <x-hero.text>
            Use the powerful search tool to find your
            {{ $category->name ?? 'vehicles' }} based on multiple search criteria:
            Manufacturer, Model, Year, Price Range, Vehicle Type, etc...
        </x-hero.text>

        <x-hero.button :category="$category" :href="route('vehicle.search')">
            Find {{ $category->name ?? 'vehicles' }}
        </x-hero.button>

    </x-hero.slide>

    <!-- Slide 2 -->
    <x-hero.slide>

        <x-slot:image>
            <x-hero.image src="/img/car-png-39071.png" alt="Car Image" />
        </x-slot:image>

        <x-hero.heading level="h2">
            Do you want to <br>
            <strong>sell your {{ $category->singular ?? 'vehicle' }}?</strong>
        </x-hero.heading>

        <x-hero.text>
            Submit your {{ $category->singular ?? 'vehicle' }} in our user friendly interface,
            describe it, upload photos and the perfect buyer will find it...
        </x-hero.text>

        {{-- Hero button uses the singular automatically --}}
        <x-hero.button />

    </x-hero.slide>
</x-hero.slider>
