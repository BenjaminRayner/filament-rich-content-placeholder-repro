# Empty rich-content TextEntry placeholder reproduction

I'm not sure whether this is a bug or expected behavior. A TextEntry with
`placeholder('No notes')` renders blank when a registered rich-content attribute
contains only an empty paragraph, instead of displaying its placeholder.

This is a fresh Laravel application with a login-protected Filament **5.11.1**
panel and a database-backed **Notes resource** with create, edit, list, and view
pages. Notes are persisted in SQLite through Eloquent. There are no third-party
Filament plugins, custom CSS, or placeholder workarounds.

## Setup

Requires PHP with PDO SQLite and the extensions required by the locked Composer
dependencies. Verified with PHP 8.5.12.

```sh
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
php artisan filament:assets
php artisan serve
```

Open `/admin/login` on the server's address and sign in with the seeded demo account:

- Email: `demo@example.com`
- Password: `password`

Then open **Notes** at `/admin/notes`. No npm build is required.

## Reproduce

The seeder creates three persisted records:

1. **Null notes (control)**: click View. The placeholder appears.
2. **Empty paragraph (reproduction)**: click View. The entry is blank without the placeholder.
3. **Delete this content and save**: click Edit, delete all editor content, click
   **Save changes**, then click **View**. The entry is blank without the placeholder.
   Reload the view page to confirm the behavior persists after a fresh database read.

You can also create a new note, enter some content, save, then edit and delete it.
The JSON `notes` column retains the empty editor document, for example:

```json
{"type":"doc","content":[{"type":"paragraph","attrs":{"textAlign":"start"}}]}
```

The model registers `notes` as rich content. The view page uses this entry:

```php
TextEntry::make('notes')
    ->prose()
    ->placeholder('No notes');
```

## Tests

Tests use a separate SQLite in-memory database, run migrations, authenticate a
user, save through the resource's EditRecord page, and reload through ViewRecord.
They do not modify the demo database.

```sh
# Seven passing tests, including authentication, null/text controls, and persistence.
php artisan test --exclude-group=known-issue

# Intentionally fails on 5.11.1 after editing, saving to the DB, and viewing the record.
php artisan test --filter=test_empty_rich_content_shows_the_placeholder
```

The complete suite has one intentional failure demonstrating the proposed
placeholder expectation. I'm unsure whether semantic emptiness is intended to
be part of Filament's placeholder behavior.

Relevant files:

- `app/Models/Note.php`: array cast and rich-content registration.
- `app/Filament/Resources/Notes/NoteResource.php`: RichEditor form and TextEntry infolist.
- `app/Filament/Resources/Notes/Pages/`: standard Filament resource pages.
- `database/migrations/2026_11_03_203928_create_notes_table.php`: nullable JSON column.
- `database/seeders/DatabaseSeeder.php`: demo account and comparison records.
- `tests/Feature/RichContentPlaceholderTest.php`: database-backed controls and failing assertion.

Verified locally with PHP 8.5.12, Laravel 13.40.0, Livewire 4.4.8, and Filament 5.11.1.