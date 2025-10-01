<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('WiFi Rates') }}
        </h2>
    </x-slot>

    <div class="py-4 container">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($esps as $esp)
            @php
                $rates = $esp->rates ?? [];
            @endphp

            <div class="card mb-5 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-bold d-flex justify-content-between align-items-center">
                        <span class="d-flex justify-content-center align-items-center flex-column">
                            {{ $esp->name ?? 'Vendo #'.$esp->id }}
                            {{ $esp->name ?? 'Vendo ID: '.$esp->device_id }}
                        </span>
                        {{-- Add Rate Button --}}
                        <button class="btn btn-primary btn-sm mb-3"
                                data-bs-toggle="modal"
                                data-bs-target="#addRateModal{{ $esp->id }}">
                            + Add Rate
                        </button>
                        
                    </h6>

                    

                    {{-- Rates Table --}}
                    @if(empty($rates))
                        <p class="text-muted">No rates found. Click “Add Rate”.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Coin (₱)</th>
                                        <th>Time</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($rates as $coin => $minutes)
                                    @php
                                        $mins = (int)$minutes;
                                        if($mins >= 60){
                                            $days = floor($mins/1440);
                                            $hours = floor(($mins%1440)/60);
                                            $remMins = $mins%60;
                                            $display = '';
                                            if($days>0) $display .= "$days day".($days>1?'s ':' ');
                                            if($hours>0) $display .= "$hours hour".($hours>1?'s ':' ');
                                            if($remMins>0) $display .= "$remMins minute".($remMins>1?'s':'');
                                        } else {
                                            $display = "$mins minute".($mins>1?'s':'');
                                        }
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">₱{{ $coin }}</td>
                                        <td>{{ $display }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRateModal{{ $esp->id }}_{{ $coin }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('esp8266s.deleteRate', [$esp->id, $coin]) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete ₱{{ $coin }} rate?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editRateModal{{ $esp->id }}_{{ $coin }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('esp8266s.updateRate', [$esp->id, $coin]) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit ₱{{ $coin }} Rate</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label class="form-label">Minutes</label>
                                                        <input type="number" name="minutes" class="form-control" value="{{ $minutes }}" min="1" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Add Modal --}}
            <div class="modal fade" id="addRateModal{{ $esp->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('esp8266s.storeRate', $esp->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Rate</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Coin (₱)</label>
                                    <input type="number" name="coin" class="form-control" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Time (minutes)</label>
                                    <input type="number" name="minutes" class="form-control" min="1" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Rate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="alert alert-info text-center">You have no ESP8266 devices.</div>
        @endforelse
    </div>
</x-app-layout>
