<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Habitacione;
use App\Servicio;
use App\Persona;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServicioStoreDuplicatePreventionTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $habitacion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first() ?? User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->habitacion = Habitacione::first() ?? Habitacione::create([
            'nombre' => '101',
            'status' => 'Disponible',
            'categoria_id' => 1,
            'level_id' => 1,
        ]);
    }

    public function test_prevents_duplicate_service_when_room_is_already_occupied()
    {
        $this->actingAs($this->user);

        // Mark room as Ocupada
        $this->habitacion->status = 'Ocupada';
        $this->habitacion->save();

        $initialServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();

        $response = $this->post(route('servicio.store'), [
            'id_habitacion' => $this->habitacion->id,
            'nombreHabitacion' => $this->habitacion->nombre,
            'cliente_id' => 1,
            'modo_pago' => 'contado',
            'tipo_pago' => 'Dolar',
            'monto_dejado' => 20,
            'total_costo' => 20,
        ]);

        $response->assertRedirect('checkout');
        $response->assertSessionHas('status_danger');

        $finalServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();
        $this->assertEquals($initialServicesCount, $finalServicesCount);
    }

    public function test_prevents_duplicate_service_when_room_already_has_initiated_service()
    {
        $this->actingAs($this->user);

        $this->habitacion->status = 'Disponible';
        $this->habitacion->save();

        // Create an existing active service
        $activeService = Servicio::create([
            'num_servicio' => 'CS-TEST-001',
            'operador' => $this->user->name,
            'status_servicio' => 'Iniciado',
            'habitacion_id' => $this->habitacion->id,
            'nombre_habitacion' => $this->habitacion->nombre,
            'detalle_habitacion' => 'Matrimonial',
            'tipo_habitacion' => 'Normal',
            'horario' => '12 Horas',
            'fecha_entrada' => now()->toDateString(),
            'hora_entrada' => now()->toTimeString(),
            'fecha_salida' => now()->addHours(12)->toDateString(),
            'hora_salida' => now()->addHours(12)->toTimeString(),
            'modo_pago' => 'Contado',
            'tipo_pago' => 'Dolar',
            'status' => 'Pagado',
            'estado' => 'Aceptada',
            'total_venta' => 20,
            'persona_id' => 1,
            'user_id' => $this->user->id,
            'caja_id' => 1,
        ]);

        $initialServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();

        $response = $this->post(route('servicio.store'), [
            'id_habitacion' => $this->habitacion->id,
            'nombreHabitacion' => $this->habitacion->nombre,
            'cliente_id' => 1,
            'modo_pago' => 'contado',
            'tipo_pago' => 'Dolar',
            'monto_dejado' => 20,
            'total_costo' => 20,
        ]);

        $response->assertRedirect('checkout');
        $response->assertSessionHas('status_danger');

        $finalServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();
        $this->assertEquals($initialServicesCount, $finalServicesCount);
    }

    public function test_prevents_concurrent_submission_via_cache_lock()
    {
        $this->actingAs($this->user);

        $this->habitacion->status = 'Disponible';
        $this->habitacion->save();

        // Acquire lock manually to simulate concurrent in-flight request
        $lockKey = 'lock_servicio_hab_' . $this->habitacion->id;
        $lock = Cache::lock($lockKey, 15);
        $lock->get();

        $initialServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();

        $response = $this->post(route('servicio.store'), [
            'id_habitacion' => $this->habitacion->id,
            'nombreHabitacion' => $this->habitacion->nombre,
            'cliente_id' => 1,
            'modo_pago' => 'contado',
            'tipo_pago' => 'Dolar',
            'monto_dejado' => 20,
            'total_costo' => 20,
        ]);

        $response->assertRedirect('checkout');
        $response->assertSessionHas('status_danger');

        $finalServicesCount = Servicio::where('habitacion_id', $this->habitacion->id)->count();
        $this->assertEquals($initialServicesCount, $finalServicesCount);

        $lock->release();
    }
}