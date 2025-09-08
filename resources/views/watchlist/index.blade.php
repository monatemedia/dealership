<x-app-layout>
    <main>
        <!-- New Vehicles -->
        <section>
          <div class="container">
            <div class="flex justify-between items-center">
                <h2>My Favourite Vehicles</h2>
                @if ($vehicles->total() > 0)
                <div class="pagination-summary">
                    <p>
                        Showing {{ $vehicles->firstItem() }} to {{ $vehicles->lastItem() }} of {{ $vehicles->total() }} results
                    </p>
                </div>
                @endif
            </div>

            <div class="vehicle-items-listing">
                @foreach($vehicles as $vehicle)
                    <x-vehicle-item :vehicle="$vehicle" :isInWatchlist="true"/>
                @endforeach
            </div>

            @if ($vehicles->count() === 0)
                <div class="text-center p-large">
                    You don't have any favourite vehicles.
                </div>
            @endif

            {{ $vehicles->onEachSide(1)->links() }}
          </div>
        </section>
        <!--/ New Vehicles -->
      </main>
</x-app-layout>
