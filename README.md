# Empty rich-content TextEntry placeholder reproduction

I'm not sure whether this is a bug or expected behavior. A TextEntry with
`placeholder('No notes')` renders blank when a registered rich-content attribute
contains only an empty paragraph, instead of displaying its placeholder.

This is a fresh Laravel application with Filament **5.11.1**. The lock file pins
the dependencies. There are no third-party Filament plugins, custom CSS, or
placeholder workarounds. No database or login is needed; the model is unsaved
and the editor state is held in the Livewire component for the current session.

## Setup

Requires PHP 8.3+ and Composer, with the extensions required by Laravel/Filament.

```sh
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php artisan filament:assets
php artisan serve
```

Open `/admin/reproduction` on the server's address. No npm build, migrations,
or account creation is required. The demo panel is deliberately unauthenticated.

## Reproduce

1. Open the reproduction page. It starts with an empty paragraph document.
2. Observe that the **Rendered TextEntry** section is blank, without "No notes".
3. Click **Load null (control)**. The placeholder appears.
4. Enter some text in the editor and click **Save editor content**.
5. Delete all editor content and click **Save editor content** again.
6. The saved state contains an empty paragraph and the TextEntry is blank again.

You can also click **Load empty paragraph (reproduction)** for a deterministic
reproduction without editing.

The JSON document is nonempty structurally, but has no visible content:

```json
{"type":"doc","content":[{"type":"paragraph","attrs":{"textAlign":"start"}}]}
```

The model registers `notes` as rich content. The entry is simply:

```php
TextEntry::make('notes')
    ->prose()
    ->placeholder('No notes');
```

## Tests

```sh
# Passing controls: page loads, null shows placeholder, editor retains empty JSON.
php artisan test --exclude-group=known-issue

# Intentionally fails on 5.11.1: expects the placeholder for an empty paragraph.
php artisan test --filter=test_empty_rich_content_shows_the_placeholder
```

The complete suite therefore has one intentional failure demonstrating the
reported behavior. This is a proposed expectation, not a claim that Filament
documents semantic emptiness as part of the placeholder contract.

Relevant files:

- `app/Models/Note.php`: array cast and rich-content registration.
- `app/Filament/Pages/Reproduction.php`: editor, save action, and TextEntry.
- `resources/views/filament/pages/reproduction.blade.php`: comparison controls.
- `tests/Feature/RichContentPlaceholderTest.php`: controls and failing assertion.

Verified locally with PHP 8.5.12, Laravel 13.40.0, Livewire 4.4.8, and Filament 5.11.1.
