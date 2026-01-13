<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Servis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServisTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function klijent_moze_da_vidi_svoje_servise()
    {
        $user = User::factory()->klijent()->create();
        
        Servis::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)
            ->get('/home');
        
        $response->assertStatus(200);
        $response->assertViewIs('servisi.index');
        $response->assertSee('Moji servisi');
    }

    /** @test */
    public function samo_klijenti_mogu_da_zakazuju_servise()
    {
        $klijent = User::factory()->klijent()->create();
        
        $response = $this->actingAs($klijent)
            ->get('/servisi/zakazi');
        
        $response->assertStatus(200);
        $response->assertViewIs('servisi.zakazi');
        
        $serviser = User::factory()->serviser()->create();
        
        $response = $this->actingAs($serviser)
            ->get('/servisi/zakazi');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function klijent_moze_da_zakaze_novi_servis()
    {
        $user = User::factory()->klijent()->create();
        
        $response = $this->actingAs($user)
            ->post('/zakazi-servis', [
                'vozilo' => 'BG123AB',
                'tip_tahografa' => 'digitalni',
                'termin' => now()->addDays(3)->format('Y-m-d H:i'),
                'telefon' => '0631234567',
                'opis_problema' => 'Test opis problema',
            ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('servisi', [
            'vozilo' => 'BG123AB',
            'tip_tahografa' => 'digitalni',
            'user_id' => $user->id,
            'status' => 'zakazano',
        ]);
    }

    /** @test */
    public function serviser_moze_da_vidi_servisne_zahteve()
    {
        $serviser = User::factory()->serviser()->create();
        
        Servis::factory()->count(3)->create(['status' => 'zakazano']);
        
        $response = $this->actingAs($serviser)
            ->get('/serviser/servisni-zahtevi');
        
        $response->assertStatus(200);
        $response->assertViewIs('serviser.servisni-zahtevi');
        $response->assertSee('Servisni zahtevi');
    }

    /** @test */
    public function samo_serviseri_i_admini_mogu_pristupiti_serviser_stranicama()
    {
        $klijent = User::factory()->klijent()->create();
        
        $response = $this->actingAs($klijent)
            ->get('/serviser/dashboard');
        
        $response->assertStatus(403);
        
        $serviser = User::factory()->serviser()->create();
        
        $response = $this->actingAs($serviser)
            ->get('/serviser/dashboard');
        
        $response->assertStatus(200);
        
        $admin = User::factory()->admin()->create();
        
        $response = $this->actingAs($admin)
            ->get('/serviser/dashboard');
        
        $response->assertStatus(200);
    }
}