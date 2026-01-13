<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servisni zahtevi - ServisTahografa</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E8F0FE;
        }
        
        .status-zakazano {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-u_dijagnostici {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-u_popravci {
            background-color: #fed7aa;
            color: #c2410c;
        }
        
        .status-zavrseno {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body class="min-h-screen bg-[#E8F0FE]">
    
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md border-b border-[#CCCCCC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('serviser.dashboard') }}" class="text-2xl font-bold text-[#1A73E8]">
                            ServisTahografa
                        </a>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-6">
                        <a href="{{ route('serviser.dashboard') }}" 
                           class="text-gray-700 hover:text-[#1A73E8] px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Home
                        </a>
                        <a href="{{ route('serviser.servisni-zahtevi') }}" 
                           class="text-gray-700 hover:text-[#1A73E8] px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Servisni zahtevi
                        </a>
                        <a href="{{ route('serviser.popravke') }}" 
                           class="text-gray-700 hover:text-[#1A73E8] px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Popravke
                        </a>
                        <a href="{{ route('serviser.zalihe') }}" 
                           class="text-gray-700 hover:text-[#1A73E8] px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Zalihe
                        </a>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">
                            {{ Auth::user()->role }}
                        </span>
                    </span>
                    
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-[#1A73E8] text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Servisni zahtevi</h1>
            <p class="text-gray-600">Pregled svih zakazanih servisa tahografa koji čekaju obradu</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-[#CCCCCC] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Ukupno zahteva</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $servisi->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-[#CCCCCC] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Danas</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $danas = $servisi->filter(function($servis) {
                                    return \Carbon\Carbon::parse($servis->termin)->isToday();
                                })->count();
                            @endphp
                            {{ $danas }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-[#CCCCCC] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Sutra</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $sutra = $servisi->filter(function($servis) {
                                    return \Carbon\Carbon::parse($servis->termin)->isTomorrow();
                                })->count();
                            @endphp
                            {{ $sutra }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-[#CCCCCC] p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Ove nedelje</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $oveNedelje = $servisi->filter(function($servis) {
                                    return \Carbon\Carbon::parse($servis->termin)->isCurrentWeek();
                                })->count();
                            @endphp
                            {{ $oveNedelje }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-[#CCCCCC] p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Filteri</h3>
            <form method="GET" action="{{ route('serviser.servisni-zahtevi') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pretraga po vozilu</label>
                    <input type="text" 
                           name="vozilo" 
                           value="{{ request('vozilo') }}"
                           placeholder="Unesite registraciju ili marku"
                           class="w-full px-4 py-2 border border-[#CCCCCC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1A73E8] focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pretraga po klijentu</label>
                    <input type="text" 
                           name="klijent" 
                           value="{{ request('klijent') }}"
                           placeholder="Unesite ime klijenta"
                           class="w-full px-4 py-2 border border-[#CCCCCC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1A73E8] focus:border-transparent">
                </div>
                
                <div class="flex items-end space-x-3">
                    <button type="submit" 
                            class="bg-[#1A73E8] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Primeni filtere
                    </button>
                    
                    @if(request()->anyFilled(['vozilo', 'klijent']))
                        <a href="{{ route('serviser.servisni-zahtevi') }}"
                           class="text-gray-600 hover:text-gray-900 underline text-sm">
                            Resetuj filtere
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Services Table -->
        <div class="bg-white rounded-lg shadow-sm border border-[#CCCCCC] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Klijent
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vozilo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Termin
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tip tahografa
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Akcije
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($servisi as $servis)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Client Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">
                                                    {{ substr($servis->user->name ?? 'K', 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $servis->user->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $servis->telefon ?? 'Nema telefona' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Vehicle Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $servis->vozilo ?? ($servis->voziloRelacija->registracija ?? 'N/A') }}
                                    </div>
                                    @if($servis->voziloRelacija)
                                        <div class="text-sm text-gray-500">
                                            {{ $servis->voziloRelacija->marka ?? '' }} {{ $servis->voziloRelacija->model ?? '' }}
                                        </div>
                                    @endif
                                    @if($servis->opis_problema)
                                        <div class="text-xs text-gray-400 mt-1 truncate max-w-xs">
                                            {{ Str::limit($servis->opis_problema, 50) }}
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Date Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ \Carbon\Carbon::parse($servis->termin)->format('d.m.Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($servis->termin)->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($servis->termin)->diffForHumans() }}
                                    </div>
                                </td>
                                
                                <!-- Tachograph Type -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $servis->tip_tahografa == 'digitalni' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($servis->tip_tahografa) }}
                                    </span>
                                </td>
                                
                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = 'status-' . str_replace(' ', '_', $servis->status);
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($servis->status) }}
                                    </span>
                                </td>
                                
                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Start Diagnosis Button -->
                                        <form action="{{ route('serviser.pokreni-dijagnostiku', $servis->id) }}" 
                                              method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Da li ste sigurni da želite da pokrenete dijagnostiku za ovaj servis?')"
                                                    class="bg-[#1A73E8] text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors text-sm font-medium">
                                                Pokreni dijagnostiku
                                            </button>
                                        </form>
                                        
                                        <!-- Service Record Button -->
                                        <a href="{{ route('serviser.servisni-zapis', $servis->id) }}"
                                           class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors text-sm font-medium">
                                            Servisni zapis
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nema servisnih zahteva</h3>
                                    <p class="mt-1 text-sm text-gray-500">Trenutno nema servisnih zahteva na čekanju.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Information Section -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Serviser Info -->
            <div class="bg-white rounded-lg shadow-sm border border-[#CCCCCC] p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informacije o serviseru</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <span class="text-gray-600">Ime i prezime:</span>
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <span class="text-gray-600">Email adresa:</span>
                        <span class="font-medium">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <span class="text-gray-600">Uloga:</span>
                        <span class="font-medium text-blue-600">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Broj dodeljenih servisa:</span>
                        <span class="font-medium">
                            @php
                                $assignedCount = \App\Models\Servis::where('serviser_id', Auth::id())
                                    ->whereIn('status', ['u dijagnostici', 'u popravci'])
                                    ->count();
                            @endphp
                            {{ $assignedCount }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-200 p-6">
                <h3 class="text-lg font-medium text-blue-900 mb-4">📋 Uputstvo za rad</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-blue-800">Pre pokretanja dijagnostike, proverite detalje servisa i informacije o vozilu.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-blue-800">Proverite dostupnost potrebnih delova u zalihama pre započinjanja popravke.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-blue-800">Obavezno unesite broj plombe nakon završetka servisa za sertifikaciju.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-12 border-t border-[#CCCCCC] bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm">
                © {{ date('Y') }} ServisTahografa. Sva prava zadržana.
            </p>
        </div>
    </footer>

    <!-- JavaScript for confirmations -->
    <script>
        // Dodatna JavaScript funkcionalnost
        document.addEventListener('DOMContentLoaded', function() {
            // Dodajemo hover efekte za dugmad
            const buttons = document.querySelectorAll('button, a');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.2s ease';
                });
            });
            
            // Formatiranje datuma za bolji prikaz
            const dateCells = document.querySelectorAll('td:nth-child(3)');
            dateCells.forEach(cell => {
                const dateText = cell.textContent;
                // Možete dodati dodatno formatiranje ako je potrebno
            });
        });
    </script>
</body>
</html>