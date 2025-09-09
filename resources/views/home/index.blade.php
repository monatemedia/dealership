{{-- resource/views/home/index.blade.php --}}

<x-app-layout title="Home Page">

    <!-- Home Slider -->
    <section class="hero-slider">
        <!-- Carousel wrapper -->
        <div class="hero-slides">
            <!-- Item 1 -->
            <div class="hero-slide">
                <div class="container">
                    <div class="slide-content">
                        <h1 class="hero-slider-title">
                            Buy <strong>{{ $category->name ?? 'The Best Vehicles' }}</strong> <br />
                            in your region
                        </h1>
                        <div class="hero-slider-content">
                            <p>
                                Use the powerful search tool to find your
                                {{ $category->name ?? 'vehicles' }} based on multiple search criteria:
                                Manufacturer, Model, Year, Price Range, Vehicle Type, etc...
                            </p>
                            <a href="{{ route('vehicle.search') }}" class="btn btn-hero-slider">
                                Find A Vehicle
                            </a>
                        </div>
                    </div>
                    <div class="slide-image">
                        <img src="/img/car-png-39071.png" alt="" class="img-responsive" />
                    </div>
                </div>
            </div>
            <!-- Item 2 -->
            <div class="hero-slide">
                <div class="flex container">
                    <div class="slide-content">
                        <h2 class="hero-slider-title">
                            Do you want to <br />
                            <strong>sell your {{ $category->name ?? 'vehicle' }}?</strong>
                        </h2>
                        <div class="hero-slider-content">
                            <p>
                                Submit your {{ $category->name ?? 'vehicle' }} in our user friendly interface, describe it,
                                upload photos and the perfect buyer will find it...
                            </p>
                            <a href="{{ route('vehicle.create') }}" class="btn btn-hero-slider">
                                Add Your Vehicle
                            </a>
                        </div>
                    </div>
                    <div class="slide-image">
                        <img src="/img/car-png-39071.png" alt="" class="img-responsive" />
                    </div>
                </div>
            </div>
            <!-- Carousel Controls -->
            <button type="button" class="hero-slide-prev">
                <svg style="width: 18px" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </button>
            <button type="button" class="hero-slide-next">
                <svg style="width: 18px" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </button>
        </div>
    </section>
    <!--/ Home Slider -->


    <main>
        <x-search-form />
        <!-- New Vehicles -->
        <section>
            <div class="container">
                <h2>
                    @isset($category)
                        Vehicles in {{ $category->name }} Category
                    @else
                        Latest Vehicles Added
                    @endisset
                </h2>
                @if ($vehicles->count() > 0)
                <div class="vehicle-items-listing">
                    @foreach($vehicles as $vehicle)
                        <x-vehicle-item :$vehicle
                        :is-in-watchlist="$vehicle->favouredUsers->contains(
                        \Illuminate\Support\Facades\Auth::user()
                        )"/>
                    @endforeach
                </div>
                @else
                    <div class="text-center p-large">
                        There are no published vehicles.
                    </div>
                @endif
                {{ $vehicles->onEachSide(1)->links() }}
            </div>
        </section>
        <!--/ New Vehicles -->
    </main>

</x-app-layout>
