<x-app-layout>

    <div class="min-h-screen bg-[#E8F0FE] py-10">

        <div class="max-w-6xl mx-auto mb-6">
            <h1 class="text-3xl font-bold mb-2">Moji servisi</h1>
            <p>Pregled svih servisa tahografa</p>
        </div>
        

        <div class="max-w-6xl mx-auto p-6 bg-white border border-[#CCCCCC] shadow-md rounded-md">

            <!-- Poruka o uspehu -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Pretraga po vozilu -->
            <form method="GET" action="{{ route('servisi.index') }}" class="mb-6">
                <div class="flex items-center gap-3">
                    <input
                        type="text"
                        name="vozilo"
                        value="{{ request('vozilo') }}"
                        placeholder="Pretraga po nazivu vozila"
                        class="w-full md:w-1/3 px-4 py-2 border border-[#CCCCCC] rounded focus:outline-none focus:ring-2 focus:ring-[#1A73E8]"
                    >

                    <button
                        type="submit"
                        class="bg-[#1A73E8] text-white px-5 py-2 rounded hover:bg-blue-700"
                    >
                        Pretraži
                    </button>

                    @if(request('vozilo'))
                        <a href="{{ route('servisi.index') }}"
                        class="text-gray-600 underline text-sm">
                            Resetuj
                        </a>
                    @endif
                </div>
            </form>

            <!-- Tabela sa servisima -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-white border-b border-[#CCCCCC]">
                            <th class="p-3 border-r border-[#CCCCCC]">Vozilo</th>
                            <th class="p-3 border-r border-[#CCCCCC]">Datum</th>
                            <th class="p-3 border-r border-[#CCCCCC]">Status</th>
                            <th class="p-3 border-r border-[#CCCCCC]">Serviser</th>
                            <th class="p-3">Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servisi as $servis)
                            <tr class="bg-white border-b border-[#CCCCCC] hover:bg-gray-50">
                                <td class="p-3 border-r border-[#CCCCCC]">
                                    {{ $servis->vozilo 
                                        ?? ($servis->vozilo_id 
                                            ? optional($servis->vozilo)->registracija 
                                            : 'N/A') 
                                    }}
                                </td>

                                <td class="p-3 border-r border-[#CCCCCC]">
                                    {{ \Carbon\Carbon::parse($servis->termin)->format('d.m.Y') }}
                                </td>
                                <td class="p-3 border-r border-[#CCCCCC]">
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
                                        {{ ucfirst($servis->status) }}
                                    </span>
                                </td>
                                <td class="p-3 border-r border-[#CCCCCC]">
                                    {{ $servis->serviser ? $servis->serviser->name : 'Nije dodeljeno' }}
                                </td>
                                <td class="p-3">
                                    <a href="{{ route('servisi.show', $servis->id) }}" class="bg-[#1A73E8] text-white px-3 py-1 rounded hover:bg-blue-700">
                                        Detalji
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-center text-gray-500">Nemate nijedan zakazani servis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
