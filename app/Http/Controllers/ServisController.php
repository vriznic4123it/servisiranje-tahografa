<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servis;
use App\Models\Vozilo;

class ServisController extends Controller
{
    // Metoda za listu servisa (index)
    public function index(Request $request)
    {
        $query = Servis::where('user_id', auth()->id());

        // Filter po nazivu vozila
        if ($request->filled('vozilo')) {
            $query->where('vozilo', 'like', '%' . $request->vozilo . '%');
        }

        $servisi = $query->orderBy('termin', 'desc')->get();

        return view('servisi.index', compact('servisi'));   
    }


    // Metoda za prikaz forme zakazivanja
    public function createZakazivanje()
    {
        $vozila = Vozilo::where('user_id', auth()->id())->get();
        return view('servisi.zakazi', compact('vozila'));
    }

    // Metoda za snimanje zakazanog servisa
    public function zakazi(Request $request)
    {
        $request->validate([
        'vozilo' => 'required|string|max:255',
        'tip_tahografa' => 'required|in:analogni,digitalni',
        'termin' => 'required|date|after:now',
        'telefon' => 'required|string|max:20',
    ]);

    // Pronađi ili kreiraj vozilo za ulogovanog korisnika
    $vozilo = Vozilo::firstOrCreate(
        ['registracija' => $request->vozilo, 'user_id' => auth()->id()],
        ['marka' => 'N/A', 'model' => 'N/A']
    );

    // Snimamo servis sa vozilo_id iznad
    Servis::create([
        'user_id' => auth()->id(),
        'vozilo_id' => $vozilo->id,
        'vozilo' => $request->vozilo,
        'tip_tahografa' => $request->tip_tahografa,
        'opis_problema' => $request->opis_problema,
        'termin' => $request->termin,
        'telefon' => $request->telefon,
        'status' => 'zakazano',
    ]);

        return redirect()->back()->with('success', 'Servis zakazan!');
    }

    // Standardne resursne metode (mogu biti prazne za sada)
    public function create()
    {
        return redirect()->route('servis.createZakazi');
    }

    public function store(Request $request)
    {
        // Možemo koristiti samo zakazi()
    }

    public function show($id)
    {
        $servis = Servis::where('user_id', auth()->id())->findOrFail($id);
        return view('servisi.show', compact('servis'));
    }

    public function edit(Servis $servis)
    {
        return view('servisi.edit', compact('servis'));
    }

    public function update(Request $request, Servis $servis)
    {
        $servis->update($request->all());
        return redirect()->route('servisi.index')->with('success', 'Servis ažuriran!');
    }

    public function destroy(Servis $servis)
    {
        $servis->delete();
        return redirect()->route('servisi.index')->with('success', 'Servis obrisan!');
    }
}
