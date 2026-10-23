<?php

namespace Tests\Feature;

use App\Enums\ContractType;
use App\Enums\InsuranceType;
use App\Models\Car;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_dashboard_loads()
    {
        $this->actingAs($this->user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_dashboard_requires_auth()
    {
        $this->get(route('dashboard'))->assertRedirect('/login');
    }

    public function test_store_creates_contract()
    {
        $car = Car::factory()->create();
        $contact = Contact::factory()->create();

        $this->actingAs($this->user)
            ->post(route('contracts.store'), [
                'type' => ContractType::BuyContract,
                'date' => '15.01.2025',
                'price' => 150000,
                'car_id' => $car->id,
                'contact_id' => $contact->id,
                'insurance_type' => InsuranceType::Keine,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('contracts', [
            'car_id' => $car->id,
            'contact_id' => $contact->id,
            'type' => ContractType::BuyContract,
        ]);
    }

    public function test_store_validates_required_fields()
    {
        $this->actingAs($this->user)
            ->post(route('contracts.store'), [])
            ->assertSessionHasErrors(['type', 'date', 'price', 'car_id', 'contact_id']);
    }

    public function test_store_validates_price_minimum()
    {
        $car = Car::factory()->create();
        $contact = Contact::factory()->create();

        $this->actingAs($this->user)
            ->post(route('contracts.store'), [
                'type' => ContractType::BuyContract,
                'date' => '15.01.2025',
                'price' => 0,
                'car_id' => $car->id,
                'contact_id' => $contact->id,
            ])
            ->assertSessionHasErrors('price');
    }

    public function test_store_validates_delivery_date_after_date()
    {
        $car = Car::factory()->create();
        $contact = Contact::factory()->create();

        $this->actingAs($this->user)
            ->post(route('contracts.store'), [
                'type' => ContractType::BuyContract,
                'date' => '15.06.2025',
                'delivery_date' => '01.01.2025',
                'price' => 150000,
                'car_id' => $car->id,
                'contact_id' => $contact->id,
            ])
            ->assertSessionHasErrors('delivery_date');
    }

    public function test_update_modifies_contract()
    {
        $contract = Contract::factory()->create(['price' => 100000]);

        $this->actingAs($this->user)
            ->put(route('contracts.update', $contract), [
                'date' => '20.01.2025',
                'price' => 200000,
                'insurance_type' => InsuranceType::Keine,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'price' => 200000,
        ]);
    }

    public function test_destroy_soft_deletes_contract()
    {
        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('contracts.destroy', $contract))
            ->assertRedirect();

        $this->assertSoftDeleted('contracts', ['id' => $contract->id]);
    }

    public function test_deleted_contract_can_still_be_shown()
    {
        $contract = Contract::factory()->create();
        $contract->delete();

        $this->actingAs($this->user)
            ->get(route('contracts.show', $contract))
            ->assertOk();
    }

    public function test_restore_restores_contract()
    {
        $contract = Contract::factory()->create();
        $contract->delete();

        $this->actingAs($this->user)
            ->post(route('contracts.restore', $contract))
            ->assertRedirect();

        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'deleted_at' => null,
        ]);
    }
}
