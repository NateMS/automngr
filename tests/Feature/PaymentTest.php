<?php

namespace Tests\Feature;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_store_creates_payment()
    {
        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->post(route('payments.store', $contract), [
                'date' => '15.01.2025',
                'amount' => 5000,
                'type' => PaymentType::Cash,
                'contract_id' => $contract->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('payments', [
            'contract_id' => $contract->id,
            'amount' => 5000,
        ]);
    }

    public function test_store_validates_amount_minimum()
    {
        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->post(route('payments.store', $contract), [
                'date' => '15.01.2025',
                'amount' => 0,
                'type' => PaymentType::Cash,
                'contract_id' => $contract->id,
            ])
            ->assertSessionHasErrors('amount');
    }

    public function test_store_validates_required_fields()
    {
        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->post(route('payments.store', $contract), [])
            ->assertSessionHasErrors(['date', 'amount', 'type', 'contract_id']);
    }

    public function test_destroy_deletes_payment()
    {
        $contract = Contract::factory()->create();
        $payment = Payment::factory()->create(['contract_id' => $contract->id]);

        $this->actingAs($this->user)
            ->delete(route('payments.destroy', [$contract, $payment]))
            ->assertRedirect();

        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    public function test_destroy_requires_correct_contract()
    {
        $contract1 = Contract::factory()->create();
        $contract2 = Contract::factory()->create();
        $payment = Payment::factory()->create(['contract_id' => $contract1->id]);

        $this->actingAs($this->user)
            ->delete(route('payments.destroy', [$contract2, $payment]))
            ->assertNotFound();
    }
}
