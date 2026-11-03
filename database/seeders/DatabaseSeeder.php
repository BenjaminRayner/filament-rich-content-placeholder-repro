<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Note;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo User', 'password' => 'password', 'email_verified_at' => now()],
        );

        Note::factory()->nullNotes()->create(['title' => 'Null notes (control)']);
        Note::factory()->emptyParagraph()->create(['title' => 'Empty paragraph (reproduction)']);
        Note::factory()->create(['title' => 'Delete this content and save']);
    }
}
