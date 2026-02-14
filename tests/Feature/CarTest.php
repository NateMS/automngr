<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_cars_index_requires_auth()
    {
        $this->get(route('cars'))->assertRedirect('/login');
    }

    public function test_cars_index_loads()
    {
        $this->actingAs($this->user)
            ->get(route('cars'))
            ->assertOk();
    }

    public function test_store_creates_car()
    {
        $carModel = CarModel::factory()->create();

        $this->actingAs($this->user)
            ->post(route('cars.store'), [
                'stammnummer' => '123.456.789',
                'vin' => 'WVWZZZ3CZWE123456',
                'colour' => 'Schwarz',
                'initial_date' => '01.01.2020',
                'kilometers' => '50000',
                'car_model_id' => $carModel->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cars', [
            'stammnummer' => '123.456.789',
            'vin' => 'WVWZZZ3CZWE123456',
        ]);
    }

    public function test_store_validates_stammnummer_format()
    {
        $carModel = CarModel::factory()->create();

        $this->actingAs($this->user)
            ->post(route('cars.store'), [
                'stammnummer' => 'invalid',
                'vin' => 'WVWZZZ3CZWE123456',
                'initial_date' => '01.01.2020',
                'kilometers' => '50000',
                'car_model_id' => $carModel->id,
            ])
            ->assertSessionHasErrors('stammnummer');
    }

    public function test_store_validates_vin_length()
    {
        $carModel = CarModel::factory()->create();

        $this->actingAs($this->user)
            ->post(route('cars.store'), [
                'stammnummer' => '123.456.789',
                'vin' => 'SHORT',
                'initial_date' => '01.01.2020',
                'kilometers' => '50000',
                'car_model_id' => $carModel->id,
            ])
            ->assertSessionHasErrors('vin');
    }

    public function test_destroy_soft_deletes_car()
    {
        $car = Car::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('cars.destroy', $car))
            ->assertRedirect();

        $this->assertSoftDeleted('cars', ['id' => $car->id]);
    }
}
