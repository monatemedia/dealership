{{-- resources/views/vehicle/images.blade.php --}}

<x-app-layout title="My Vehicles" bodyClass="page-my-vehicles">
    <main>
        <div>
            <div class="container">
                <h1 class="vehicle-details-page-title">
                    Manage Images for {{ $vehicle->getTitle() }}
                </h1>
                {{-- Sortable List Component --}}
                <x-sortable-vehicle-images :vehicle="$vehicle"/>
            </div>
        </div>
    </main>
</x-app-layout>
