<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Habitacione;
use App\Reservation;
use App\Servicio;
use App\Caja;
use App\Cat;
use App\Horario;
use App\Persona;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $caja;
    protected $habitacion;
    protected $categoria;
    protected $horario;
    protected $persona;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();

        $this->caja = Caja::where('estado', 'Abierta')->first() ?? Caja::first();

        $this->categoria = Cat::where('estado', 'Activa')->first() ?? Cat::first();

        $this->horario = Horario::first();

        $this->habitacion = Habitacione::first();

        $this->persona = Persona::first() ?? Persona::create([
            'nombre' => 'Test Persona',
            'tipo_persona' => 'Cliente',
            'tipo_documento' => 'CI.V',
            'num_documento' => 'V-12345678',
        ]);
    }

    public function test_cannot_activate_reservation_when_room_is_occupied()
    {
        $this->actingAs($this->user);

        // Mark room as Ocupada
        $this->habitacion->status = 'Ocupada';
        $this->habitacion->save();

        $reservation = Reservation::create([
            'start' => now()->format('Y-m-d H:i:s'),
            'end' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'title' => 'Reserva Test',
            'nombreCliente' => 'Cliente Ocupado',
            'cedulaCliente' => 'V-999999',
            'telefonoContacto' => '04141234567',
            'tipoHabitacion' => $this->categoria->nombre,
            'tipoServicio' => $this->horario->tipo,
            'cantidad' => 1,
            'numHabitacion' => $this->habitacion->nombre,
            'cat_id' => $this->categoria->id,
            'horario_id' => $this->horario->id,
            'habitacione_id' => $this->habitacion->id,
            'persona_id' => $this->persona->id,
            'user_id' => $this->user->id,
            'caja_id' => $this->caja->id,
            'color' => '#C67110',
            'status' => 'Pendiente',
            'precio' => 30,
            'montoPago' => 30,
        ]);

        $initialServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();

        $response = $this->patch(route('reservations.update', $reservation->id), [
            'id' => $reservation->id,
            '_processService' => 'true',
            'habitacione_id' => $this->habitacion->id,
            'numHabitacion' => $this->habitacion->nombre,
            'tipoHabitacion' => $this->categoria->nombre,
            'tipoServicio' => $this->horario->tipo,
            'nombreCliente' => 'Cliente Ocupado',
            'cedulaCliente' => 'V-999999',
            'precio' => 30,
            'montoPago' => 30,
            'start' => now()->format('Y-m-d H:i'),
            'end' => now()->addDays(1)->format('Y-m-d H:i'),
            'persona_id' => $this->persona->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'type' => 'danger',
        ]);

        // Reservation should NOT be processed
        $reservation->refresh();
        $this->assertEquals('Pendiente', $reservation->status);

        // No new service should be created
        $finalServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();
        $this->assertEquals($initialServicesCount, $finalServicesCount);
    }

    public function test_can_update_reservation_client_and_service_data()
    {
        $this->actingAs($this->user);

        $reservation = Reservation::create([
            'start' => now()->format('Y-m-d H:i:s'),
            'end' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'title' => 'Reserva Original',
            'nombreCliente' => 'Nombre Antiguo',
            'cedulaCliente' => 'V-111111',
            'telefonoContacto' => '04140000000',
            'tipoHabitacion' => $this->categoria->nombre,
            'tipoServicio' => $this->horario->tipo,
            'cantidad' => 1,
            'numHabitacion' => $this->habitacion->nombre,
            'cat_id' => $this->categoria->id,
            'horario_id' => $this->horario->id,
            'habitacione_id' => $this->habitacion->id,
            'persona_id' => $this->persona->id,
            'user_id' => $this->user->id,
            'caja_id' => $this->caja->id,
            'color' => '#C67110',
            'status' => 'Pendiente',
            'precio' => 20,
            'montoPago' => 20,
        ]);

        $response = $this->patch(route('reservations.update', $reservation->id), [
            'id' => $reservation->id,
            '_processService' => 'false',
            'nombreCliente' => 'Nuevo Nombre Cliente',
            'cedulaCliente' => 'V-222222',
            'telefonoContacto' => '04149999999',
            'tipoHabitacion' => $this->categoria->nombre,
            'tipoServicio' => $this->horario->tipo,
            'cantidad' => 2,
            'precio' => 50,
            'montoPago' => 50,
            'cat_id' => $this->categoria->id,
            'horario_id' => $this->horario->id,
            'habitacione_id' => $this->habitacion->id,
            'numHabitacion' => $this->habitacion->nombre,
            'start' => now()->addDays(2)->format('Y-m-d H:i'),
            'end' => now()->addDays(4)->format('Y-m-d H:i'),
            'persona_id' => $this->persona->id,
            'status' => 'Pendiente',
            'color' => '#C67110',
        ]);

        $response->assertStatus(200);

        $reservation->refresh();
        $this->assertEquals('Nuevo Nombre Cliente', $reservation->nombreCliente);
        $this->assertEquals('V-222222', $reservation->cedulaCliente);
        $this->assertEquals('04149999999', $reservation->telefonoContacto);
        $this->assertEquals(2, $reservation->cantidad);
        $this->assertEquals(50, (float)$reservation->precio);
    }

    public function test_available_rooms_query_excludes_occupied_rooms()
    {
        $this->actingAs($this->user);

        // Habitacion ocupada
        $this->habitacion->status = 'Ocupada';
        $this->habitacion->save();

        // Crear o asegurar una habitación disponible en la misma categoría
        $availableRoom = Habitacione::where('cat_id', $this->habitacion->cat_id)
            ->where('id', '!=', $this->habitacion->id)
            ->where('status', '!=', 'Ocupada')
            ->first();

        $response = $this->getJson(route('search.habitaciones', [
            'cat_id' => $this->habitacion->cat_id,
            'fechaEntrada' => now()->toDateString(),
            'horaEntrada' => '14:00',
            'fechaSalida' => now()->addDay()->toDateString(),
            'horaSalida' => '14:00',
        ]));

        $response->assertStatus(200);
        $roomIds = collect($response->json())->pluck('id')->toArray();
        $this->assertNotContains($this->habitacion->id, $roomIds);

        if ($availableRoom) {
            $this->assertContains($availableRoom->id, $roomIds);
        }
    }
}
