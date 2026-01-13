<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servis;
use App\Models\Vozilo;
use App\Models\User;

class ServisController extends Controller
{
    // Metoda za listu servisa (index) - samo za klijente
    public function index(Request $request)
    {
        // Ako je korisnik serviser, preusmeri ga na serviser stranicu
        if (auth()->user()->role === 'serviser' || auth()->user()->role === 'admin') {
            return redirect()->route('serviser.servisi.index');
        }

        // Samo klijenti vide svoje servise
        $query = Servis::with(['serviser', 'voziloRelacija'])
            ->where('user_id', auth()->id());

        // Filter po nazivu vozila
        if ($request->filled('vozilo')) {
            $query->where('vozilo', 'like', '%' . $request->vozilo . '%');
        }

        // Filter po statusu
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $servisi = $query->orderBy('termin', 'desc')->paginate(10);

        return view('servisi.index', compact('servisi'));   
    }

    // Metoda za prikaz forme zakazivanja
    public function createZakazivanje()
    {
        // Samo klijenti mogu zakazivati servise
        if (auth()->user()->role !== 'klijent') {
            abort(403, 'Samo klijenti mogu zakazivati servise.');
        }

        $vozila = Vozilo::where('user_id', auth()->id())->get();
        return view('servisi.zakazi', compact('vozila'));
    }

    // Metoda za snimanje zakazanog servisa
    public function zakazi(Request $request)
    {
        // Samo klijenti mogu zakazivati servise
        if (auth()->user()->role !== 'klijent') {
            abort(403, 'Samo klijenti mogu zakazivati servise.');
        }

        $request->validate([
            'vozilo' => 'required|string|max:255',
            'tip_tahografa' => 'required|in:analogni,digitalni',
            'termin' => 'required|date|after:now',
            'telefon' => 'required|string|max:20',
            'opis_problema' => 'nullable|string|max:1000',
        ]);

        // Proveri da li termin nije zauzet (opciono)
        $postojiTermin = Servis::where('termin', $request->termin)
            ->where('status', '!=', 'zavrseno')
            ->exists();
            
        if ($postojiTermin) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['termin' => 'Termin je već zauzet. Molimo odaberite drugi termin.']);
        }

        // Pronađi ili kreiraj vozilo za ulogovanog korisnika
        $vozilo = Vozilo::firstOrCreate(
            ['registracija' => $request->vozilo, 'user_id' => auth()->id()],
            ['marka' => 'N/A', 'model' => 'N/A']
        );

        // Snimamo servis
        Servis::create([
            'user_id' => auth()->id(),
            'vozilo_id' => $vozilo->id,
            'vozilo' => $request->vozilo,
            'tip_tahografa' => $request->tip_tahografa,
            'opis_problema' => $request->opis_problema,
            'termin' => $request->termin,
            'telefon' => $request->telefon,
            'status' => 'zakazano',
            'serviser_id' => null,
        ]);

        return redirect()->route('servisi.index')
            ->with('success', 'Servis je uspešno zakazan! Kontaktiraćemo vas u vezi potvrde termina.');
    }

    // Prikaz određenog servisa
    public function show($id)
    {
        $servis = Servis::with(['serviser', 'voziloRelacija', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('servisi.show', compact('servis'));
    }

    // Prikaz forme za izmenu servisa
    public function edit($id)
    {
        $servis = Servis::where('user_id', auth()->id())->findOrFail($id);
        
        // Klijent može da menja samo servise koji su zakazani
        if ($servis->status !== 'zakazano') {
            abort(403, 'Možete menjati samo zakazane servise.');
        }

        return view('servisi.edit', compact('servis'));
    }

    // Ažuriranje servisa
    public function update(Request $request, $id)
    {
        $servis = Servis::where('user_id', auth()->id())->findOrFail($id);
        
        // Klijent može da menja samo servise koji su zakazani
        if ($servis->status !== 'zakazano') {
            abort(403, 'Možete ažurirati samo zakazane servise.');
        }

        $request->validate([
            'vozilo' => 'required|string|max:255',
            'tip_tahografa' => 'required|in:analogni,digitalni',
            'termin' => 'required|date|after:now',
            'telefon' => 'required|string|max:20',
            'opis_problema' => 'nullable|string|max:1000',
        ]);

        // Ažuriraj vozilo ako je promenjeno
        if ($servis->vozilo !== $request->vozilo) {
            $vozilo = Vozilo::firstOrCreate(
                ['registracija' => $request->vozilo, 'user_id' => auth()->id()],
                ['marka' => 'N/A', 'model' => 'N/A']
            );
            $servis->vozilo_id = $vozilo->id;
        }

        $servis->update([
            'vozilo' => $request->vozilo,
            'tip_tahografa' => $request->tip_tahografa,
            'opis_problema' => $request->opis_problema,
            'termin' => $request->termin,
            'telefon' => $request->telefon,
        ]);

        return redirect()->route('servisi.index')
            ->with('success', 'Servis je uspešno ažuriran!');
    }

    // Brisanje servisa
    public function destroy($id)
    {
        $servis = Servis::where('user_id', auth()->id())->findOrFail($id);
        
        // Klijent može da obriše samo servise koji su zakazani
        if ($servis->status !== 'zakazano') {
            abort(403, 'Možete obrisati samo zakazane servise.');
        }

        $servis->delete();

        return redirect()->route('servisi.index')
            ->with('success', 'Servis je uspešno obrisan!');
    }

    // Standardne resursne metode
    public function create()
    {
        return redirect()->route('servis.createZakazi');
    }

    public function store(Request $request)
    {
        // Koristimo zakazi() metodu
        return $this->zakazi($request);
    }

    // Dodatne metode za klijente

    /**
     * Otkazivanje servisa
     */
    public function otkazi($id)
    {
        $servis = Servis::where('user_id', auth()->id())->findOrFail($id);
        
        if ($servis->status !== 'zakazano') {
            abort(403, 'Možete otkazati samo zakazane servise.');
        }

        $servis->update([
            'status' => 'otkazano',
        ]);

        return redirect()->route('servisi.index')
            ->with('success', 'Servis je uspešno otkazan!');
    }

    /**
     * Prikaz istorije servisa
     */
    public function istorija(Request $request)
    {
        $query = Servis::with(['serviser', 'voziloRelacija'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['zavrseno', 'otkazano']);

        if ($request->filled('vozilo')) {
            $query->where('vozilo', 'like', '%' . $request->vozilo . '%');
        }

        if ($request->filled('godina')) {
            $query->whereYear('termin', $request->godina);
        }

        $servisi = $query->orderBy('termin', 'desc')->paginate(10);

        return view('servisi.istorija', compact('servisi'));
    }

    /**
     * Export servisa u PDF (opciono)
     */
    public function exportPdf($id)
    {
        $servis = Servis::with(['serviser', 'voziloRelacija', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Ovde bi bio kod za generisanje PDF-a
        // return PDF::loadView('servisi.pdf', compact('servis'))->download('servis-' . $servis->id . '.pdf');
        
        return redirect()->back()->with('info', 'PDF export će biti dostupan uskoro.');
    }

    /**
     * Dashboard podaci za klijenta
     */
    public function dashboard()
    {
        $userId = auth()->id();
        
        $podaci = [
            'ukupno' => Servis::where('user_id', $userId)->count(),
            'zakazano' => Servis::where('user_id', $userId)->where('status', 'zakazano')->count(),
            'u_radu' => Servis::where('user_id', $userId)
                ->whereIn('status', ['u dijagnostici', 'u popravci'])
                ->count(),
            'zavrseno' => Servis::where('user_id', $userId)->where('status', 'zavrseno')->count(),
            'naredni' => Servis::where('user_id', $userId)
                ->where('status', 'zakazano')
                ->orderBy('termin', 'asc')
                ->first(),
            'vozila' => Vozilo::where('user_id', $userId)->count(),
        ];

        return view('dashboard', compact('podaci'));
    }
}