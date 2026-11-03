<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'notes' => ['type' => 'doc', 'content' => [
                ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Some notes']]],
            ]],
        ];
    }

    public function emptyParagraph(): static
    {
        return $this->state(fn (): array => [
            'notes' => ['type' => 'doc', 'content' => [
                ['type' => 'paragraph', 'attrs' => ['textAlign' => 'start']],
            ]],
        ]);
    }

    public function nullNotes(): static
    {
        return $this->state(fn (): array => ['notes' => null]);
    }
}
