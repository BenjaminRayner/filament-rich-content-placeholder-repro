<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\NoteFactory;

class Note extends Model implements HasRichContent
{
    use InteractsWithRichContent;

    /** @use HasFactory<NoteFactory> */
    use HasFactory;

    protected $fillable = ['title', 'notes'];

    protected function casts(): array
    {
        return ['notes' => 'array'];
    }

    public function setUpRichContent(): void
    {
        $this->registerRichContent('notes')->json();
    }
}
