<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_upload_stores_document()
    {
        Storage::fake('documents');
        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->post(route('documents.store'), [
                'document' => UploadedFile::fake()->create('test.pdf', 100, 'application/pdf'),
                'documentable_type' => 'contracts',
                'documentable_id' => $contract->id,
            ])
            ->assertOk();

        $this->assertDatabaseHas('documents', [
            'documentable_type' => 'contracts',
            'documentable_id' => $contract->id,
        ]);
    }

    public function test_upload_rejects_invalid_file_type()
    {
        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->post(route('documents.store'), [
                'document' => UploadedFile::fake()->create('hack.php', 100, 'application/x-httpd-php'),
                'documentable_type' => 'contracts',
                'documentable_id' => $contract->id,
            ])
            ->assertSessionHasErrors('document');
    }

    public function test_upload_rejects_oversized_file()
    {
        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->post(route('documents.store'), [
                'document' => UploadedFile::fake()->create('huge.pdf', 11000, 'application/pdf'),
                'documentable_type' => 'contracts',
                'documentable_id' => $contract->id,
            ])
            ->assertSessionHasErrors('document');
    }

    public function test_upload_rejects_invalid_documentable_type()
    {
        $this->actingAs($this->user)
            ->post(route('documents.store'), [
                'document' => UploadedFile::fake()->create('test.pdf', 100, 'application/pdf'),
                'documentable_type' => 'users',
                'documentable_id' => 1,
            ])
            ->assertSessionHasErrors('documentable_type');
    }

    public function test_destroy_deletes_document()
    {
        Storage::fake('documents');
        $document = Document::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('documents.destroy', $document))
            ->assertRedirect();

        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }
}
