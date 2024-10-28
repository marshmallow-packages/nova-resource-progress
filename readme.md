```php
use HasMetadata;
use TrackResourceProgress;

#[RequiredFilledFields(suite: 'progress', fields: ['name', 'intro', 'description'])]
#[RequiredFilledFields(suite: 'publish', fields: ['name', 'intro'])]
class Product extends ModelsProduct implements HasMedia, Sortable
```

```php
public function setPublishActions(): array
{
    return [
        \Marshmallow\ResourceProgress\Actions\FieldFilled::class,
    ];
}
```

```bash
artisan make:resource-progress-action MissingTranslationsAction
a make:resource-progress-suite SeoSuite
```

## Register your suites in the model (required action)
```php
#[ResourceProgressSuite(suite: 'progress', name: 'Progress')]
#[ResourceProgressSuite(suite: 'publish', name: 'Publish')]
#[ResourceProgressSuite(suite: 'seo', name: 'SEO')]
class Product extends ModelsProduct implements HasMedia, Sortable
```

## Register the field (required action)
```php
ResourceProgress::make(__('Progress')),
```


```php
IndexResourceProgress::make(self::class)->standalone(),
``
