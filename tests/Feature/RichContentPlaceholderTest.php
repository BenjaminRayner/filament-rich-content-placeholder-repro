<?php

namespace Tests\Feature;

use App\Filament\Resources\Notes\NoteResource;
use App\Filament\Resources\Notes\Pages\EditNote;
use App\Filament\Resources\Notes\Pages\ViewNote;
use App\Models\Note;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class RichContentPlaceholderTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_panel_requires_login(): void
    {
        $this->get('/admin/notes')->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_open_the_notes_resource(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'demo@example.com']));

        $this->get(NoteResource::getUrl('index'))->assertOk();
    }

    public function test_database_null_notes_show_the_placeholder(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'demo@example.com']));
        $note = Note::factory()->nullNotes()->create();

        Livewire::test(ViewNote::class, ['record' => $note->getKey()])
            ->assertOk()
            ->assertSee('No notes');
    }

    public function test_database_text_notes_render_without_the_placeholder(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'demo@example.com']));
        $note = Note::factory()->create();

        Livewire::test(ViewNote::class, ['record' => $note->getKey()])
            ->assertOk()
            ->assertSee('Some notes')
            ->assertDontSee('No notes');
    }

    public function test_saving_deleted_content_persists_an_empty_paragraph_in_the_database(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'demo@example.com']));
        $note = Note::factory()->create();
        $emptyDocument = ['type' => 'doc', 'content' => [['type' => 'paragraph']]];

        Livewire::test(EditNote::class, ['record' => $note->getKey()])
            ->set('data.notes', $emptyDocument)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame($emptyDocument, $note->fresh()->notes);
    }

    #[Group('known-issue')]
    public function test_empty_rich_content_shows_the_placeholder(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'demo@example.com']));
        $note = Note::factory()->create();
        $emptyDocument = ['type' => 'doc', 'content' => [['type' => 'paragraph']]];

        Livewire::test(EditNote::class, ['record' => $note->getKey()])
            ->set('data.notes', $emptyDocument)
            ->call('save')
            ->assertHasNoFormErrors();

        Livewire::test(ViewNote::class, ['record' => $note->getKey()])
            ->assertOk()
            ->assertSee('No notes');
    }
}