<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Caja;
use App\Sessioncaja;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CajaCierreTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $caja;
    protected $sessionCaja;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first() ?? User::create([
            'name' => 'Test Cashier',
            'email' => 'cashier@test.com',
            'password' => bcrypt('secret'),
        ]);

        $this->sessionCaja = Sessioncaja::create([
            'estado' => 'Abierta',
        ]);

        $existingCaja = Caja::first();
        if ($existingCaja) {
            $this->caja = $existingCaja->replicate();
            $this->caja->sessioncaja_id = $this->sessionCaja->id;
            $this->caja->estado = 'Abierta';
            $this->caja->save();
        } else {
            $this->caja = Caja::create([
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'monto_dolar_inicio' => 100,
                'total_sistema_reg' => 100,
                'user_id' => $this->user->id,
                'sessioncaja_id' => $this->sessionCaja->id,
                'estado' => 'Abierta',
            ]);
        }
    }

    public function test_caja_cierre_with_empty_inputs_succeeds_without_error()
    {
        $this->actingAs($this->user);

        $payload = [
            'caja_id' => $this->caja->id,
            'session_id' => $this->sessionCaja->id,
            'idusuario' => $this->user->id,
            'estado' => 'cierre',
            'total_dolar' => '',
            'total_peso' => '',
            'total_bolivar' => '',
            'total_punto' => '',
            'total_trans' => '',
            'total_dolar_dif' => '',
            'total_peso_dif' => '',
            'total_bolivar_dif' => '',
            'total_punto_dif' => '',
            'total_trans_dif' => '',
            'dif_moneda_dolar_to_dolar_input' => '',
            'dif_moneda_peso_to_dolar_input' => '',
            'dif_moneda_punto_to_dolar_input' => '',
            'dif_moneda_trans_to_dolar_input' => '',
            'dif_moneda_efectivo_to_dolar_input' => '',
            'dolar_sistema' => '',
            'peso_sistema' => '',
            'punto_sistema' => '',
            'trans_sistema' => '',
            'efectivo_sistema' => '',
            'total_sistema_reg_input' => '',
            'total_operador_reg_input' => '',
            'total_dif_input' => '',
            'hist_creditos_vigentes' => '',
            'hist_creditos_vencidos' => '',
            'hist_creditos_pagados' => '',
            'hist_creditos_nuevos' => '',
            'hist_total_creditos' => '',
            'stock_cierre_operador' => '',
            'observacionesStock' => '',
            'Observaciones' => 'Cierre de prueba con campos vacios',
        ];

        $response = $this->put(route('caja.update', $this->caja->id), $payload);

        $response->assertStatus(302);
        $response->assertRedirect(route('caja.index'));

        $this->caja->refresh();
        $this->assertEquals('Cerrada', $this->caja->estado);
        $this->assertEquals(0.0, (float) $this->caja->total_operador_reg);
    }

    public function test_caja_cierre_when_already_closed_redirects_with_warning()
    {
        $this->actingAs($this->user);

        $this->caja->estado = 'Cerrada';
        $this->caja->save();

        $response = $this->put(route('caja.update', $this->caja->id), [
            'caja_id' => $this->caja->id,
            'session_id' => $this->sessionCaja->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('caja.index'));
        $response->assertSessionHas('status_danger', 'Esta caja ya se encuentra cerrada.');
    }

    public function test_caja_cierre_with_nonexistent_id_redirects_with_warning()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('caja.update', 9999999), [
            'caja_id' => 9999999,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('caja.index'));
        $response->assertSessionHas('status_danger', 'No se encontró la caja especificada.');
    }
}
