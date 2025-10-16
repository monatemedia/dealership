{{-- resource/views/home/index.blade.php --}}

<x-app-layout title="Home Page">

    <!-- Home Slider -->
    <x-hero.home-slider />
    <!--/ Home Slider -->


    <main>

        {{-- Category Section --}}
        <x-category.section
            :categories="$categories"
            type="Main Category"
            pluralType="Main Categories"
            indexRouteName="main-categories.index"
            showRouteName="main-categories.show"
            :selectingForCreate="false"
        />
        {{-- /Category Section --}}

        <!-- Search Form -->
        <x-search-form />
        <!-- /Search Form -->

        <!-- New Vehicles -->
        <section>
            <div class="container">
                <h2>
                    @isset($category)
                        Latest {{ $category->name }}
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
