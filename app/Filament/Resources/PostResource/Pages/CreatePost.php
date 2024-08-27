<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\PostResource\Traits\HasEditMediaAction;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use HasEditMediaAction;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getEditMediaAction()
        ];
    }
}
