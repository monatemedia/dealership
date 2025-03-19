@php
    // Array of features
    $features = [
        'air_conditioning' => 'Air Conditioning',
        'power_windows' => 'Power Windows',
        'power_door_locks' => 'Power Door Locks',
        'abs' => 'ABS',
        'cruise_control' => 'Cruise Control',
        'bluetooth_connectivity' => 'Bluetooth Connectivity',
        'remote_start' => 'Remote Start',
        'gps_navigation' => 'GPS Navigation System',
        'heated_seats' => 'Heated Seats',
        'climate_control' => 'Climate Control',
        'rear_parking_sensors' => 'Rear Parking Sensors',
        'leather_seats' => 'Leather Seats',
    ];
@endphp

{{-- Iterate over features --}}
<div class="form-group">
    <div class="row">
        <div class="col">
            @foreach ($features as $key => $feature)
                <label class="checkbox">
                    <input
                        type="checkbox"
                        name="features[{{ $key }}]"
                        value="1"
                    />
                    {{ $feature }}
                </label>
                {{-- After six iterations and it's not the last loop --}}
                @if ($loop->iteration % 6 == 0 && !$loop->last)
                    </div> {{-- Close div --}}
                    <div class="col"> {{-- open new column div --}}
                @endif
            @endforeach
        {{-- class="class" closing div --}}
        </div>
    {{-- row closing div --}}
    </div>
{{-- form-group closing div --}}
</div>
