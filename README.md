# aaronschmied/laravel-model-history

Records the changes made to an eloquent model.

## Installation
```
composer require aaronschmied/laravel-model-history
```

The package is auto discovered.

To publish the migration file, run the following artisan command:
```
php artisan vendor:publish --provider="AaronSchmied\ModelHistory\Providers\ModelHistoryServiceProvider" --tag="migrations"
```

To change the config, publish it using the following command:
(This step is optional)

```
php artisan vendor:publish --provider="AaronSchmied\ModelHistory\Providers\ModelHistoryServiceProvider" --tag="config"
```

## Usage

Add the trait to your model class you want to record changes for:

```php
use AaronSchmied\ModelHistory\Traits\RecordsChanges;
use Illuminate\Database\Eloquent\Model;

class Example extends Model {
    use RecordsChanges;
}

```

Your model now has a relation to all the changes made:

```php
$example->changes->last();

AaronSchmied\ModelHistory\Change {
  #attributes: array:8 [
    ...
    "change_type" => "updated"
    "changes" => "{
        "before": {
            "body": "Some old content"
        },
        "after": {
            "body": "This is the new body"
        }
    }"
    "recorded_at" => "2019-06-21 23:31:15"
  ]
  ...
}
```

The change model also includes a relation to the user who made the change, as well as a timestamp when it was recorded.

You can filter the changes using the query scopes:

```php
// Get the updates on the given model, by the given user, in the last 30 days:
Change::query()
    ->whereAuthor($user)
    ->whereSubject($model)
    ->whereType(Change::TYPE_UPDATED)
    ->whereRecordedBetween(now()->subDays(30), now())
    ->get();
```
