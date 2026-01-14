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
        // Prethodno: @group servis @group klijent
        $klijent = User::factory()->create(['role' => 'klijent']);
        $servis = Servis::factory()->create(['user_id' => $klijent->id]);
        
        $response = $this->actingAs($klijent)
            ->get('/servisi');
        
        $response->assertStatus(200)
            ->assertSee($servis->opis);
    }

    /** @test */
    public function samo_klijenti_mogu_da_zakazuju_servise()
    {
        // Prethodno: @group servis @group klijent
        $serviser = User::factory()->create(['role' => 'serviser']);
        
        $response = $this->actingAs($serviser)
            ->get('/servisi/create');
        
        $response->assertForbidden();
    }

    /** @test */
    public function klijent_moze_da_zakaze_novi_servis()
    {
        // Prethodno: @group servis @group klijent
        $klijent = User::factory()->create(['role' => 'klijent']);
        
        $response = $this->actingAs($klijent)
            ->post('/servisi', [
                'opis' => 'Test servis',
                'datum' => '2024-12-01',
                'vreme' => '10:00'
            ]);
        
        $response->assertRedirect('/servisi');
        $this->assertDatabaseHas('servisi', ['opis' => 'Test servis']);
    }

    /** @test */
    public function serviser_moze_da_vidi_servisne_zahteve()
    {
        // Prethodno: @group servis @group serviser
        $serviser = User::factory()->create(['role' => 'serviser']);
        $servis = Servis::factory()->create();
        
        $response = $this->actingAs($serviser)
            ->get('/serviser/servisi');
        
        $response->assertStatus(200)
            ->assertSee($servis->opis);
    }

    /** @test */
    public function samo_serviseri_i_admini_mogu_pristupiti_serviser_stranicama()
    {
        // Prethodno: @group servis @group serviser
        $klijent = User::factory()->create(['role' => 'klijent']);
        
        $response = $this->actingAs($klijent)
            ->get('/serviser/dashboard');
        
        $response->assertForbidden();
    }
}