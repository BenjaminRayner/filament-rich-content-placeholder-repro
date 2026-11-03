<?php

namespace App\Filament\Pages;

use App\Models\Note;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class Reproduction extends Page
{
    protected string $view = 'filament.pages.reproduction';

    public ?array $data = [];

    public ?array $savedNotes = null;

    public function mount(): void
    {
        $this->loadEmptyParagraph();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model(Note::class)
            ->statePath('data')
            ->components([RichEditor::make('notes')->json()]);
    }

    public function notesInfolist(Schema $schema): Schema
    {
        return $schema
            ->record(new Note(['notes' => $this->savedNotes]))
            ->components([
                TextEntry::make('notes')
                    ->prose()
                    ->placeholder('No notes'),
            ]);
    }

    public function saveNotes(): void
    {
        $this->savedNotes = $this->form->getState()['notes'] ?? null;
    }

    public function loadNull(): void
    {
        $this->savedNotes = null;
        $this->form->fill(['notes' => null]);
    }

    public function loadEmptyParagraph(): void
    {
        $this->savedNotes = [
            'type' => 'doc',
            'content' => [['type' => 'paragraph', 'attrs' => ['textAlign' => 'start']]],
        ];
        $this->form->fill(['notes' => $this->savedNotes]);
    }
}
