# Filament Gallery Demo

## RelatedMediaUpload Filament Form Field

Instead of storing images json in model's field it will store 
media's (e.g. image) data in media table that morphs to the model.

It is similar to
https://github.com/filamentphp/spatie-laravel-media-library-plugin/
approach.

But I don't need the spatie media library (no complex media managing UI), 
the only thing i need is to store medias in the database and display them in the form.

```php
RelatedMediaUpload::make('attachments')
    ->multiple()
    ->reorderable() 
```

See ./tmp/SpatieMediaLibraryPlugin.php as the example.

Media table example:

- model_type: App\Models\Post 
- model_id: 1 (id of the particular post)
- collection_name: images (will be implemented later)
- filename: image.jpg
- size: 12345
- order_column: 1

