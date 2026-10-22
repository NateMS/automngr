<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_activity_log_page_loads()
    {
        $this->actingAs($this->user)
            ->get(route('activity-log'))
            ->assertOk();
    }

    public function test_activity_log_requires_auth()
    {
        $this->get(route('activity-log'))->assertRedirect('/login');
    }

    public function test_creating_model_generates_log_entry()
    {
        $this->actingAs($this->user);

        Contact::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'created',
            'subject_type' => 'contacts',
        ]);
    }

    public function test_updating_model_generates_log_entry()
    {
        $this->actingAs($this->user);

        $contact = Contact::factory()->create();
        $contact->update(['firstname' => 'Changed']);

        $activity = Activity::where('description', 'updated')
            ->where('subject_type', 'contacts')
            ->first();

        $this->assertNotNull($activity);
        $this->assertEquals('Changed', $activity->attribute_changes['attributes']['firstname']);
    }

    public function test_deleting_model_generates_log_entry()
    {
        $this->actingAs($this->user);

        $contact = Contact::factory()->create();
        $contact->delete();

        $this->assertDatabaseHas('activity_log', [
            'description' => 'deleted',
            'subject_type' => 'contacts',
            'subject_id' => $contact->id,
        ]);
    }

    public function test_log_entries_appear_on_page()
    {
        $this->actingAs($this->user);

        Contact::factory()->create(['firstname' => 'Logged']);

        $this->get(route('activity-log'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('ActivityLog/Index')
                ->has('activities.data', 1)
            );
    }
}
