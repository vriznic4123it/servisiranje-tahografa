<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\Dijagnostika;
use App\Models\Deo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiserController extends Controller
{
    /**
     * Proverava da li je korisnik serviser ili admin
     */
    private function isServiserOrAdmin()
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['serviser', 'admin']);
    }

    /**
     * Provera pristupa - koristi se na početku svake metode
     */
    private function checkAccess()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!$this->isServiserOrAdmin()) {
            abort(403, 'Nemate ovlašćenje za pristup serviserskim stranicama.');
        }
    }

    /**
     * DASHBOARD za servisera (ekran 4)
     */
    public function dashboard()
    {
        $this->checkAccess();
        
        $statistike = [
            'na_cekanju' => Servis::where('status', 'zakazano')->count(),
            'u_dijagnostici' => Servis::where('status', 'u dijagnostici')->count(),
            'u_popravci' => Servis::where('status', 'u popravci')->count(),
            'zavrseno' => Servis::where('status', 'zavrseno')->count(),
            'nepohodni_delovi' => Deo::where('kolicina', '<', 5)->count(),
        ];

        $skoriji_servisi = Servis::with('user')
            ->where('status', 'zakazano')
            ->orderBy('termin', 'asc')
            ->limit(5)
            ->get();

        return view('serviser.dashboard', compact('statistike', 'skoriji_servisi'));
    }

    /**
     * SERVISNI ZAHTEVI - ekran 8
     */
    public function servisniZahtevi(Request $request)
    {
        $this->checkAccess();
        
        $query = Servis::with(['user', 'voziloRelacija'])
            ->where('status', 'zakazano')
            ->orderBy('termin', 'asc');

        // Filteri
        if ($request->filled('vozilo')) {
            $query->where('vozilo', 'like', '%' . $request->vozilo . '%');
        }

        if ($request->filled('klijent')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->klijent . '%');
            });
        }

        $servisi = $query->get();

        return view('serviser.servisni-zahtevi', compact('servisi'));
    }

    /**
     * POKRENI DIJAGNOSTIKU - akcija iz ekrana 8
     */
    public function pokreniDijagnostiku(Request $request, $id)
    {
        $this->checkAccess();
        
        $servis = Servis::findOrFail($id);
        
        // Proveri da li servis još uvek nije dodeljen
        if ($servis->status !== 'zakazano') {
            return redirect()->back()
                ->with('error', 'Ovaj servis je već u obradi.');
        }
        
        // Dodeli servisera
        $servis->update([
            'serviser_id' => Auth::id(),
            'status' => 'u dijagnostici',
        ]);

        // Kreiraj dijagnostiku - proveri da li već postoji
        $dijagnostika = Dijagnostika::where('servis_id', $servis->id)->first();
        
        if (!$dijagnostika) {
            Dijagnostika::create([
                'servis_id' => $servis->id,
                'opis_problema' => $servis->opis_problema,
                'serviser_id' => Auth::id(),
            ]);
        }

        return redirect()->route('serviser.dijagnostika.show', $servis->id)
            ->with('success', 'Dijagnostika započeta! Sada možete uneti rezultate.');
    }

    /**
     * OTVORI SERVISNI ZAPIS - akcija iz ekrana 8
     */
    public function servisniZapis($id)
    {
        $this->checkAccess();
        
        $servis = Servis::with(['user', 'voziloRelacija'])->findOrFail($id);
        
        // Uzmi dijagnostiku ako postoji
        $dijagnostika = Dijagnostika::where('servis_id', $servis->id)->first();
        
        return view('serviser.servisni-zapis', compact('servis', 'dijagnostika'));
    }

    /**
     * DIJAGNOSTIKA - ekran 9
     */
    public function dijagnostika($id)
    {
        $this->checkAccess();
        
        $servis = Servis::with(['user', 'voziloRelacija'])->findOrFail($id);
        
        // Proveri da li servis dodeljen ovom serviseru
        if ($servis->serviser_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Ovaj servis nije dodeljen vama.');
        }
        
        // Uzmi ili kreiraj dijagnostiku
        $dijagnostika = Dijagnostika::where('servis_id', $servis->id)->first();
        
        if (!$dijagnostika) {
            $dijagnostika = Dijagnostika::create([
                'servis_id' => $servis->id,
                'opis_problema' => $servis->opis_problema,
                'serviser_id' => Auth::id(),
            ]);
        }
        
        $delovi = Deo::all();
        
        return view('serviser.dijagnostika', compact('servis', 'dijagnostika', 'delovi'));
    }

    /**
     * SAČUVAJ DIJAGNOSTIKU - akcija iz ekrana 9
     */
    public function sacuvajDijagnostiku(Request $request, $id)
    {
        $this->checkAccess();
        
        $request->validate([
            'rezultati_dijagnostike' => 'required|string',
            'preporuceni_radovi' => 'required|string',
        ]);

        // Proveri da li servis dodeljen ovom serviseru
        $servis = Servis::find($id);
        if ($servis && $servis->serviser_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Niste ovlašćeni za ovaj servis.');
        }

        // Pronađi dijagnostiku za servis
        $dijagnostika = Dijagnostika::where('servis_id', $id)->first();
        
        if (!$dijagnostika) {
            $dijagnostika = Dijagnostika::create([
                'servis_id' => $id,
                'serviser_id' => Auth::id(),
            ]);
        }
        
        $dijagnostika->update([
            'rezultati_dijagnostike' => $request->rezultati_dijagnostike,
            'preporuceni_radovi' => $request->preporuceni_radovi,
        ]);

        // Promeni status servisa
        Servis::find($id)->update(['status' => 'u popravci']);

        return redirect()->route('serviser.dashboard')
            ->with('success', 'Dijagnostika sačuvana! Servis je prebačen u popravku.');
    }

    /**
     * POPRAVKE - ekran 10
     */
    public function popravke(Request $request)
    {
        $this->checkAccess();
        
        $query = Servis::with(['user', 'voziloRelacija'])
            ->where('status', 'u popravci')
            ->orderBy('updated_at', 'desc');

        if ($request->filled('vozilo')) {
            $query->where('vozilo', 'like', '%' . $request->vozilo . '%');
        }

        $servisi = $query->get();
        $delovi = Deo::all();

        return view('serviser.popravke', compact('servisi', 'delovi'));
    }

    /**
     * ZAVRŠI POPRAVKU - akcija iz ekrana 10
     */
    public function zavrsiPopravku(Request $request, $id)
    {
        $this->checkAccess();
        
        $request->validate([
            'izvrseni_radovi' => 'required|string',
            'broj_plombe' => 'required|string|max:50',
        ]);

        // Proveri da li servis dodeljen ovom serviseru
        $servis = Servis::find($id);
        if ($servis && $servis->serviser_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Niste ovlašćeni za ovaj servis.');
        }

        $servis->update([
            'status' => 'zavrseno',
            'broj_plombe' => $request->broj_plombe,
        ]);

        // Ažuriraj dijagnostiku sa izvršenim radovima
        $dijagnostika = Dijagnostika::where('servis_id', $id)->first();
        if ($dijagnostika) {
            $dijagnostika->update([
                'izvrseni_radovi' => $request->izvrseni_radovi,
            ]);
        }

        return redirect()->route('serviser.dashboard')
            ->with('success', 'Popravka završena! Servis je označen kao završen.');
    }

    /**
     * ZALIHE - ekran 11 (read-only za servisera)
     */
    public function zalihe(Request $request)
    {
        $this->checkAccess();
        
        $query = Deo::query();

        if ($request->filled('pretraga')) {
            $query->where('naziv', 'like', '%' . $request->pretraga . '%')
                  ->orWhere('kod', 'like', '%' . $request->pretraga . '%');
        }

        $delovi = $query->orderBy('naziv')->get();

        return view('serviser.zalihe', compact('delovi'));
    }

    /**
     * LISTA AKTIVNIH SERVISA
     */
    public function aktivniServisi()
    {
        $this->checkAccess();
        
        $servisi = Servis::with(['user', 'voziloRelacija'])
            ->whereIn('status', ['u dijagnostici', 'u popravci'])
            ->orderBy('status')
            ->orderBy('termin')
            ->get();

        return view('serviser.aktivni-servisi', compact('servisi'));
    }

    /**
     * DETALJI SERVISA
     */
    public function detaljiServisa($id)
    {
        $this->checkAccess();
        
        $servis = Servis::with(['user', 'voziloRelacija', 'serviser'])->findOrFail($id);
        
        // Proveri da li servis dodeljen ovom serviseru
        if ($servis->serviser_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Niste ovlašćeni za pregled detalja ovog servisa.');
        }
        
        // Uzmi dijagnostiku ako postoji
        $dijagnostika = Dijagnostika::where('servis_id', $id)->first();

        return view('serviser.detalji-servisa', compact('servis', 'dijagnostika'));
    }
}