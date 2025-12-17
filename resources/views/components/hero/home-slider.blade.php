{{-- resources/views/components/hero/home-slider.blade.php --}}
@php
    // Determine which category to display based on what's available
    $displayCategory = $subcategory ?? $mainCategory ?? null;

    // More explicit null handling for IDE
    if ($displayCategory !== null) {
        $categoryName = $displayCategory->name;
        $categorySingular = $displayCategory->singular;
    } else {
        $categoryName = 'Vehicles';
        $categorySingular = 'vehicle';
    }
@endphp

<x-hero.slider class="main.no-padding">
    <!-- Slide 1 -->
    <x-hero.slide>
        <x-slot:image>
            <x-hero.image src="/img/car-png-39071.png" alt="Car Image" />
        </x-slot:image>

        <x-hero.heading level="h1">
           <strong>{{ $categoryName }}</strong> in <br>
            <strong>South Africa</strong>
        </x-hero.heading>

        <x-hero.text>
            Use the powerful search tool to find your
            {{ strtolower($categoryName) }} based on multiple search criteria:
            Manufacturer, Model, Year, Price Range, Vehicle Type, etc...
        </x-hero.text>

        <x-hero.button :href="route('vehicle.search')">
            Find {{ $categoryName }}
        </x-hero.button>
    </x-hero.slide>

    <!-- Slide 2 -->
    <x-hero.slide>
        <x-slot:image>
            <x-hero.image src="/img/car-png-39071.png" alt="Car Image" />
        </x-slot:image>

        <x-hero.heading level="h2">
            Do you want to <br>
            <strong>sell your {{ $categorySingular }}?</strong>
        </x-hero.heading>

        <x-hero.text>
            Submit your {{ $categorySingular }} in our user friendly interface,
            describe it, upload photos and the perfect buyer will find it...
        </x-hero.text>

        {{-- Hero button uses the category context automatically --}}
        <x-hero.button />
    </x-hero.slide>
</x-hero.slider>
