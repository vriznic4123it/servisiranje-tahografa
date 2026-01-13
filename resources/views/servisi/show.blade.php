<x-app-layout>
    <div class="min-h-screen bg-[#E8F0FE] py-10">
        <div class="max-w-4xl mx-auto p-6 bg-white border border-[#CCCCCC] shadow-md rounded-md">
            <h1 class="text-3xl font-bold mb-6">Detalji servisa</h1>

            <div class="space-y-4">
                <div>
                    <strong>Vozilo:</strong> {{ $servis->vozilo ?? 'Nije uneto' }}
                </div>
                <div>
                    <strong>Tip tahografa:</strong> {{ $servis->tip_tahografa ?? 'Nije uneto' }}
                </div>
                <div>
                    <strong>Opis problema:</strong> {{ $servis->opis_problema ?? 'Nije uneto' }}
                </div>
                <div>
                    <strong>Termin:</strong> {{ $servis->termin ? \Carbon\Carbon::parse($servis->termin)->format('d.m.Y H:i') : 'Nije uneto' }}
                </div>
                <div>
                    <strong>Telefon:</strong> {{ $servis->telefon ?? 'Nije uneto' }}
                </div>
                <div>
                    <strong>Status:</strong> 
                    @php
                        $statusColors = [
                            'zakazano' => 'bg-blue-100 text-blue-800',
                            'u dijagnostici' => 'bg-yellow-100 text-yellow-800',
                            'u popravci' => 'bg-orange-100 text-orange-800',
                            'zavrseno' => 'bg-green-100 text-green-800',
                        ];
                        $statusClass = $statusColors[strtolower($servis->status)] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 rounded {{ $statusClass }}">
                        {{ ucfirst($servis->status) ?? 'Nije uneto' }}
                    </span>
                </div>
                <div>
                    <strong>Serviser:</strong> {{ $servis->serviser ? $servis->serviser->name : 'Nije dodeljeno' }}
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('servisi.index') }}" class="bg-[#1A73E8] text-white px-4 py-2 rounded hover:bg-blue-700">
                    Nazad na servise
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
