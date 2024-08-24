# Filament Gallery Demo

## Plan

Try to create media table likewise to the 
https://github.com/filamentphp/spatie-laravel-media-library-plugin/
approach.

Media table example:

- model_type: App\Models\Post 
- model_id: 1 (id of the particular post)
- collection_name: images (will be implemented later)
- filename: image.jpg
- size: 12345
- order_column: 1
- custom_field_1: value1 
- custom_field_2: value2

Probably we will start with even less abstraction 
and will create a separate table for each model.

Then post_images table will look like:

- post_id: 1
- filename: image.jpg
- size: 12345
- order_column: 1
- custom_field_1: value1
- custom_field_2: value2
