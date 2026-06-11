![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Nova Resource Progress

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/resource-progress.svg?style=flat-square)](https://packagist.org/packages/marshmallow/resource-progress)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/resource-progress.svg?style=flat-square)](https://packagist.org/packages/marshmallow/resource-progress)

A Laravel Nova field that visualises how "complete" a resource is. You define one or
more *suites* of *actions* (checks) on your model, and the field renders a progress
indicator per suite based on how many of those checks pass. Progress is recalculated
automatically whenever a tracked model is created, updated or restored, and can be
recalculated in bulk from Nova index actions.

## Installation

Install the package via Composer:

```bash
composer require marshmallow/resource-progress
```

The package is auto-discovered through its `FieldServiceProvider`, so no manual
provider registration is required.

Optionally publish the config file:

```bash
php artisan vendor:publish --provider="Marshmallow\ResourceProgress\FieldServiceProvider"
```

## Configuration

The published `config/resource-progress.php` exposes the following options:

| Key | Default | Description |
| --- | --- | --- |
| `progress` | `[\Marshmallow\ResourceProgress\Actions\FieldFilled::class]` | The default actions run for the `progress` suite. Add config keys named after other suites to give them default actions too. |
| `after_commit` | `false` | When `true`, progress updates triggered by the model observer are dispatched after all database transactions have committed. |

## Usage

### 1. Track a model

Add the `TrackResourceProgress` trait to any Eloquent model you want to track, and
declare one or more suites with the `ResourceProgressSuite` attribute. The attribute
is repeatable, so a model can have several suites.

```php
use Marshmallow\ResourceProgress\Traits\TrackResourceProgress;
use Marshmallow\ResourceProgress\Attributes\ResourceProgressSuite;

#[ResourceProgressSuite(suite: 'progress', name: 'Progress')]
#[ResourceProgressSuite(suite: 'publish', name: 'Publish', fields: ['name', 'intro'])]
class Product extends Model
{
    use TrackResourceProgress;
}
```

The package registers a model observer for every model that uses the
`TrackResourceProgress` trait. Progress is recalculated on `created`, `updated` and
`restored` events and stored on the model's `resource_progress` attribute.

### 2. Define the checks for a suite

Each suite resolves its actions in this order:

1. The actions configured under `config('resource-progress.{suite}')`.
2. The actions returned by a `set{Suite}Actions()` method on the model.

So to add checks to the `publish` suite, add a `setPublishActions()` method:

```php
use Marshmallow\ResourceProgress\Actions\FieldFilled;

public function setPublishActions(): array
{
    return [
        FieldFilled::class,
    ];
}
```

The built-in `FieldFilled` action checks that a list of fields is filled. It reads the
fields from the suite's `fields` argument, or — if present — from a
`get{Suite}RequiredFields()` method on the model:

```php
public function getProgressRequiredFields(): array
{
    return ['name', 'intro', 'description', 'supplier_id', 'product_category_id'];
}
```

### 3. Add the field to your Nova resource

Add the field to your Nova resource's `fields()` method. It reads the suites declared
on the underlying model automatically:

```php
use Marshmallow\ResourceProgress\ResourceProgress;

ResourceProgress::make(__('Progress')),
```

The field has no editable value; it only displays progress. Its appearance can be
tuned with the following fluent setters (defaults shown):

```php
ResourceProgress::make(__('Progress'))
    ->setCircleSize(20)
    ->setStrokeWidth(3)
    ->setProgressBarHeight(15)
    ->setColorRanges([
        0 => '#ef4444',
        50 => '#facc15',
        100 => '#16a34a',
    ]);
```

`setColorRanges()` maps a percentage threshold to the colour used at or above it.

### 4. Recalculate progress in bulk (optional)

Two Nova actions are provided to recalculate progress from the index:

```php
use Marshmallow\ResourceProgress\Nova\Actions\IndexResourceProgress;
use Marshmallow\ResourceProgress\Nova\Actions\IndexResourcesProgress;

public function actions(NovaRequest $request): array
{
    return [
        // Recalculate the selected resources immediately.
        IndexResourceProgress::make(),

        // Recalculate every record of the resource on the queue.
        IndexResourcesProgress::make(self::class)->standalone(),
    ];
}
```

`IndexResourcesProgress` queues an `UpdateResourceProgressJob` per record, so it is
suited to large tables.

### Writing your own checks

Generate a custom action or suite with the included Artisan commands:

```bash
php artisan make:resource-progress-action MissingTranslationsAction
php artisan make:resource-progress-suite SeoSuite
```

A custom action extends `ResourceProgressAction` and implements `handle()`. Call
`incrementCheckCount()` for every check performed and `fail()` (with a message) for
every check that does not pass:

```php
use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class HasIntroAction extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        $this->incrementCheckCount();

        if (! $this->resource->intro) {
            $this->fail(__('The intro is missing.'));
        }
    }
}
```

### Events

A `Marshmallow\ResourceProgress\Events\ProgressUpdated` event is dispatched each time
a suite's progress is recalculated. It carries the `$model`, the `$suite` and the full
`$current_progress` array, so you can listen for it to react to progress changes.

## Credits

- [Marshmallow](https://github.com/marshmallow-packages)
- [All Contributors](https://github.com/marshmallow-packages/nova-resource-progress/contributors)

## Security Vulnerabilities

Please report security vulnerabilities by email to [security@marshmallow.dev](mailto:security@marshmallow.dev) rather than via the public issue tracker.

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
