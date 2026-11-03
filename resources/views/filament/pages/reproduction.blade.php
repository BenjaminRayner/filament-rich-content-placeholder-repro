<x-filament-panels::page>
    <p>I’m not sure whether this is a bug or expected behavior. An empty rich-editor paragraph renders blank below without the “No notes” placeholder.</p>
    <form wire:submit="saveNotes">
        {{ $this->form }}
        <x-filament::button type="submit">Save editor content</x-filament::button>
    </form>
    <div>
        <x-filament::button wire:click="loadNull" color="gray">Load null (control)</x-filament::button>
        <x-filament::button wire:click="loadEmptyParagraph" color="gray">Load empty paragraph (reproduction)</x-filament::button>
    </div>
    <x-filament::section heading="Rendered TextEntry">
        {{ $this->notesInfolist }}
    </x-filament::section>
    <x-filament::section heading="Saved state">
        <pre>{{ json_encode($savedNotes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
    </x-filament::section>
</x-filament-panels::page>
