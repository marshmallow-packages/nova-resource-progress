```php
use HasMetadata;
use TrackResourceProgress;

#[ResourceProgressSuite(suite: 'progress', name: 'Progress')]
#[ResourceProgressSuite(suite: 'publish', name: 'Publish', fields: ['name', 'intro'])]
class Product extends ModelsProduct implements HasMedia, Sortable
{
    // Optional if you dont provide the fields in the attribute
    public function getProgressRequiredFields(): array
    {
        return ['name', 'intro', 'description', 'supplier_id', 'product_category_id'];
    }
}


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
// Selected resources
IndexResourceProgress::make(),

// All resources
IndexResourcesProgress::make(self::class)->standalone(),
``
