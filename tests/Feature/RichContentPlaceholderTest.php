<?php

namespace Tests\Feature;

use App\Filament\Pages\Reproduction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class RichContentPlaceholderTest extends TestCase
{
    public function test_reproduction_page_loads_without_login_or_database(): void
    {
        $this->get('/admin/reproduction')->assertOk();
    }

    public function test_null_notes_show_the_placeholder(): void
    {
        $page = new Reproduction();
        $page->savedNotes = null;
        $entry = $page->notesInfolist(Schema::make($page))->getComponents()[0];

        $this->assertStringContainsString('No notes', $entry->toEmbeddedHtml());
    }

    public function test_saving_an_empty_paragraph_keeps_the_json_document(): void
    {
        $emptyDocument = ['type' => 'doc', 'content' => [['type' => 'paragraph']]];

        Livewire::test(Reproduction::class)
            ->set('data.notes', $emptyDocument)
            ->call('saveNotes')
            ->assertHasNoErrors()
            ->assertSet('savedNotes', $emptyDocument);
    }

    #[Group('known-issue')]
    public function test_empty_rich_content_shows_the_placeholder(): void
    {
        $page = new Reproduction();
        $page->savedNotes = ['type' => 'doc', 'content' => [['type' => 'paragraph']]];
        /** @var TextEntry $entry */
        $entry = $page->notesInfolist(Schema::make($page))->getComponents()[0];

        $this->assertStringContainsString('No notes', $entry->toEmbeddedHtml());
    }
}
