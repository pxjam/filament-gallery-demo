<?php

namespace App\Filament\Resources\PostResource\Traits;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

trait HasEditMediaAction
{
    protected function getEditMediaAction(): Action
    {
        return Action::make('editMedia')
            ->label('Edit Media')
            ->form([
                TextInput::make('mediaName')
                    ->label('Media name')
                    ->required(),
            ])
            ->fillForm(function (Post $record, array $arguments): array {
                $mediaId = $arguments['mediaId'];
                $mediaData = $record->media()->where('id', $mediaId)->first();
                return [
                    'mediaName' => $mediaData->name,
                ];
            })
            ->action(function (array $data, Post $record, array $arguments): void {
                $mediaId = $arguments['mediaId'];
                $media = $record->media()->where('id', $mediaId)->first();
                $media->name = $data['mediaName'];
                $media->save();
            });
    }
}
