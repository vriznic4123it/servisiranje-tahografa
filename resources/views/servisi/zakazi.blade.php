<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white border border-gray-300 shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-6">Zakazivanje servisa</h1>

        <!-- Uspešno -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Greške -->
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('servis.zakazi') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Vozilo -->
            <div>
                <label for="vozilo" class="block text-sm font-medium text-gray-700">Vozilo</label>
                <input type="text" name="vozilo" id="vozilo"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       placeholder="Unesite naziv vozila" value="{{ old('vozilo') }}">
            </div>

            <!-- Tip tahografa -->
            <div>
                <label for="tip_tahografa" class="block text-sm font-medium text-gray-700">Tip tahografa</label>
                <select name="tip_tahografa" id="tip_tahografa"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Odaberi tip</option>
                    <option value="analogni" {{ old('tip_tahografa') == 'analogni' ? 'selected' : '' }}>Analogni</option>
                    <option value="digitalni" {{ old('tip_tahografa') == 'digitalni' ? 'selected' : '' }}>Digitalni</option>
                </select>
            </div>

            <!-- Opis problema -->
            <div>
                <label for="opis_problema" class="block text-sm font-medium text-gray-700">Opis problema (opciono)</label>
                <textarea name="opis_problema" id="opis_problema"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                          rows="3">{{ old('opis_problema') }}</textarea>
            </div>

            <!-- Termin -->
            <div>
                <label for="termin" class="block text-sm font-medium text-gray-700">Datum i vreme</label>
                <input type="datetime-local" name="termin" id="termin"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('termin') }}">
            </div>

            <!-- Telefon -->
            <div>
                <label for="telefon" class="block text-sm font-medium text-gray-700">Kontakt telefon</label>
                <input type="text" name="telefon" id="telefon"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       placeholder="Unesite broj telefona" value="{{ old('telefon') }}">
            </div>

            <!-- Dugme za potvrdu -->
            <button type="submit"
                    class="inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Potvrdi zakazivanje
            </button>

            <!-- Dugme za otkazivanje -->
            <a href="{{ route('home') }}"
            class="inline-flex items-center justify-center bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-500">
                Otkaži
            </a>

        </form>
    </div>
</x-app-layout>
