<?php

namespace App\Forms\Components;

use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\Media;

class RelatedMediaUpload extends FileUpload
{
    protected string $view = 'forms.components.related-media-upload';

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadStateFromRelationshipsUsing(function (RelatedMediaUpload $component, ?Model $record): void {
            if (!$record) {
                return;
            }

            $mediaFiles = Media::where('model_type', get_class($record))
                ->where('model_id', $record->getKey())
                ->orderBy('order_column')
                ->get();

            $state = $mediaFiles->map(function ($media) {
                return $media->id;
            })->toArray();

            $component->state($state);
        });

        $this->afterStateHydrated(static function (BaseFileUpload $component, string|array|null $state): void {
            if (is_array($state)) {
                return;
            }

            $component->state([]);
        });

        $this->beforeStateDehydrated(null);

        $this->dehydrated(false);

        $this->getUploadedFileUsing(function (RelatedMediaUpload $component, string $file): ?array {
            $media = Media::find($file);

            if (!$media) {
                return null;
            }

            return [
                'name' => $media->name,
                'size' => $media->size,
                'type' => $media->mime_type,
                'url' => asset('storage/uploads/' . $media->file_name),
            ];
        });

        $this->saveRelationshipsUsing(static function (RelatedMediaUpload $component) {
            //            $component->deleteAbandonedFiles();
            $component->saveUploadedFiles();
        });

        $this->saveUploadedFileUsing(
            function (RelatedMediaUpload $component, TemporaryUploadedFile $file, ?Model $record): ?string {
                if (!$record) {
                    return null;
                }

                $filename = $component->getUploadedFileNameForStorage($file);
                $modelType = get_class($record);
                $modelId = $record->getKey();

                $order = Media::where('model_type', $modelType)
                    ->where('model_id', $modelId)
                    ->max('order_column') + 1 ?? 0;

                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $attributes = [
                    'model_type' => $modelType,
                    'model_id' => $modelId,
                    'name' => $name,
                    'file_name' => $filename,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'collection_name' => 'default',
                    'order_column' => $order,
                ];

                $media = Media::create($attributes);

                $file->storeAs('public/uploads', $filename);

                return $media->id;
            }
        );

        $this->deleteUploadedFileUsing(function (RelatedMediaUpload $component, string $file, ?Model $record): void {
            if (!$record) {
                return;
            }

            $media = Media::find($file);
            if ($media) {
                $media->delete();
            }
        });

        $this->reorderUploadedFilesUsing(function (RelatedMediaUpload $component, ?Model $record, array $state): array {
            $ids = array_filter(array_values($state));

            foreach ($ids as $order => $id) {
                Media::where('id', $id)->update(['order_column' => $order + 1]);
            }

            return $state;
        });
    }
}
