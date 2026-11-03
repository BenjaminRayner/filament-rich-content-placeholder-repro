<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Model;

class Note extends Model implements HasRichContent
{
    use InteractsWithRichContent;

    protected $fillable = ['notes'];

    protected function casts(): array
    {
        return ['notes' => 'array'];
    }

    public function setUpRichContent(): void
    {
        $this->registerRichContent('notes')->json();
    }
}
