<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_contacts_index_requires_auth()
    {
        $this->get(route('contacts'))->assertRedirect('/login');
    }

    public function test_contacts_index_loads()
    {
        $this->actingAs($this->user)
            ->get(route('contacts'))
            ->assertOk();
    }

    public function test_store_creates_contact()
    {
        $this->actingAs($this->user)
            ->post(route('contacts.store'), [
                'firstname' => 'Max',
                'lastname' => 'Muster',
                'email' => 'max@example.com',
                'phone' => '+41 79 123 45 67',
                'address' => 'Hauptstrasse 1',
                'zip' => '8000',
                'city' => 'Zürich',
                'country' => 'CH',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'firstname' => 'Max',
            'lastname' => 'Muster',
        ]);
    }

    public function test_store_validates_email()
    {
        $this->actingAs($this->user)
            ->post(route('contacts.store'), [
                'firstname' => 'Max',
                'lastname' => 'Muster',
                'email' => 'not-an-email',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_update_modifies_contact()
    {
        $contact = Contact::factory()->create();

        $this->actingAs($this->user)
            ->put(route('contacts.update', $contact), [
                'firstname' => 'Updated',
                'lastname' => 'Name',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'firstname' => 'Updated',
        ]);
    }

    public function test_destroy_soft_deletes_contact()
    {
        $contact = Contact::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('contacts.destroy', $contact))
            ->assertRedirect();

        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    public function test_restore_restores_contact()
    {
        $contact = Contact::factory()->create();
        $contact->delete();

        $this->actingAs($this->user)
            ->post(route('contacts.restore', $contact))
            ->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'deleted_at' => null,
        ]);
    }
}
