<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dijagnostika tahografa - ServisTahografa</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background-color: #E8F0FE;
            font-family: 'Inter', sans-serif;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
</head>
<body class="min-h-screen bg-[#E8F0FE] font-sans">
    
    <!-- Navbar će se automatski uključiti -->

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dijagnostika tahografa</h1>
                    <p class="text-gray-600">Unesite rezultate dijagnostike i preporučene radove</p>
                </div>
                
                <!-- Back Button -->
                <a href="{{ route('serviser.servisni-zahtevi') }}" 
                   class="text-[#1A73E8] hover:text-blue-700 font-medium">
                    ← Nazad na servisne zahteve
                </a>
            </div>
            
            <!-- Service Info Card -->
            <div class="mt-6 bg-white border border-[#CCCCCC] rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Klijent</p>
                        <p class="font-medium text-gray-900">{{ $servis->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $servis->telefon ?? 'Nema telefona' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Vozilo</p>
                        <p class="font-medium text-gray-900">
                            {{ $servis->vozilo ?? ($servis->voziloRelacija->registracija ?? 'N/A') }}
                        </p>
                        @if($servis->voziloRelacija)
                            <p class="text-sm text-gray-500">
                                {{ $servis->voziloRelacija->marka ?? '' }} {{ $servis->voziloRelacija->model ?? '' }}
                            </p>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Termin i status</p>
                        <p class="font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($servis->termin)->format('d.m.Y H:i') }}
                        </p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($servis->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Form for Diagnosis -->
            <div class="lg:col-span-2">
                <!-- Form for Diagnosis Results -->
                <form action="{{ route('serviser.dijagnostika.sacuvaj', $servis->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Problem Description -->
                    <div class="bg-white border border-[#CCCCCC] rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Opis problema</h3>
                        <div class="bg-gray-50 border border-[#CCCCCC] rounded p-4">
                            <p class="text-gray-700">
                                {{ $servis->opis_problema ?? 'Klijent nije naveo opis problema.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Diagnosis Results -->
                    <div class="bg-white border border-[#CCCCCC] rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Rezultati dijagnostike</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="rezultati_dijagnostike" class="block text-sm font-medium text-gray-700 mb-2">
                                    Detaljni rezultati dijagnostike *
                                </label>
                                <textarea 
                                    id="rezultati_dijagnostike" 
                                    name="rezultati_dijagnostike" 
                                    rows="6"
                                    class="w-full px-3 py-2 border border-[#CCCCCC] rounded focus:outline-none focus:ring-2 focus:ring-[#1A73E8] focus:border-transparent"
                                    placeholder="Unesite detaljne rezultate dijagnostike..."
                                    required>{{ old('rezultati_dijagnostike', $dijagnostika->rezultati_dijagnostike ?? '') }}</textarea>
                                @error('rezultati_dijagnostike')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="preporuceni_radovi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Preporučeni radovi *
                                </label>
                                <textarea 
                                    id="preporuceni_radovi" 
                                    name="preporuceni_radovi" 
                                    rows="4"
                                    class="w-full px-3 py-2 border border-[#CCCCCC] rounded focus:outline-none focus:ring-2 focus:ring-[#1A73E8] focus:border-transparent"
                                    placeholder="Unesite preporučene radove i delove..."
                                    required>{{ old('preporuceni_radovi', $dijagnostika->preporuceni_radovi ?? '') }}</textarea>
                                @error('preporuceni_radovi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Error Messages Area -->
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Popravite sledeće greške:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('serviser.servisni-zahtevi') }}" 
                           class="px-4 py-2 border border-[#CCCCCC] rounded text-gray-700 hover:bg-gray-50">
                            Otkaži
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-[#1A73E8] text-white rounded hover:bg-blue-700 font-medium">
                            Sačuvaj dijagnostiku
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Parts Table -->
            <div class="lg:col-span-1">
                <!-- Available Parts Table -->
                <div class="bg-white border border-[#CCCCCC] rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Dostupni delovi</h3>
                        <span class="text-sm text-gray-500">
                            {{ $delovi->count() }} delova
                        </span>
                    </div>
                    
                    <!-- Search -->
                    <div class="mb-4">
                        <input type="text" 
                               id="partsSearch"
                               placeholder="Pretraži delove..."
                               class="w-full px-3 py-2 border border-[#CCCCCC] rounded focus:outline-none focus:ring-2 focus:ring-[#1A73E8]">
                    </div>
                    
                    <!-- Parts Table -->
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Naziv
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategorija
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cena
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Zaliha
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="partsTableBody">
                                @foreach($delovi as $deo)
                                    <tr class="hover:bg-gray-50 parts-row">
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $deo->naziv }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                            Tahograf
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                            @if($deo->cena)
                                                {{ number_format($deo->cena, 2) }} RSD
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @if($deo->kolicina > 10)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $deo->kolicina }}
                                                </span>
                                            @elseif($deo->kolicina > 3)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    {{ $deo->kolicina }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ $deo->kolicina }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($delovi->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="mt-2">Trenutno nema delova u zalihama.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Brze akcije</h4>
                        <div class="space-y-2">
                            <button type="button" 
                                    onclick="copyToClipboard('Senzor brzine')"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded border border-gray-200">
                                📋 Kopiraj "Senzor brzine"
                            </button>
                            <button type="button" 
                                    onclick="copyToClipboard('Elektronička plomba')"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded border border-gray-200">
                                📋 Kopiraj "Elektronička plomba"
                            </button>
                            <button type="button" 
                                    onclick="copyToClipboard('Display tahografa')"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded border border-gray-200">
                                📋 Kopiraj "Display tahografa"
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Diagnosis Notes -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="text-sm font-medium text-blue-900 mb-3">💡 Napomene za dijagnostiku</h4>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Proverite sve konektore i kablove</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Testirajte senzor brzine na vozilu</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Verifikujte kalibraciju tahografa</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Proverite softversku verziju</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Parts Search Functionality
        document.getElementById('partsSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.parts-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Copy to Clipboard Function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert(`Kopirano: "${text}"`);
            }).catch(err => {
                console.error('Greška pri kopiranju: ', err);
            });
        }
        
        // Character Counter for Textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            const counter = document.createElement('div');
            counter.className = 'text-xs text-gray-500 mt-1 text-right';
            counter.textContent = `${textarea.value.length} karaktera`;
            
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', function() {
                counter.textContent = `${this.value.length} karaktera`;
            });
        });
        
        // Form Validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Molimo popunite sva obavezna polja.');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }
        });
    </script>
</body>
</html>