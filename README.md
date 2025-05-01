<p align="center">
<a href="https://larazeus.com"><img src="https://larazeus.com/images/lara-zeus-mark.webp?v=3" /></a>
</p>

## A ready-to-use Marking feature for Laravel, with built-in addons for Filament.

<p align="center">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lara-zeus/mark.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/mark)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/lara-zeus/mark/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/lara-zeus/mark/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/lara-zeus/mark/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/lara-zeus/mark/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lara-zeus/mark.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/mark)

</p>

## Support Filament

<a href="https://github.com/sponsors/danharrin">
<img alt="filament-logo" src="https://larazeus.com/images/filament-sponsor-banner.webp">
</a>

## Features
- Ready-to-use Database and Application structure.
- Ready-to-use Filament form components.
- Fully customizable and extensible.

## Demo

> Visit our demo site: https://demo.larazeus.com/admin/components-demo/mark


## Full Documentation

> Visit our website to get the complete documentation: https://larazeus.com/docs/mark

## Glossary

- **Mark**: The mark itself, e.g., like, bookmark, or rating.
- **Marker**: The entity that created the mark, e.g., a User.
- **Markable**: The entity that can be marked, e.g., a Post or Comment.

---

## Installation

```bash
composer require lara-zeus/mark
```

Then, set up a [Filament Custom Theme](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) and add the following path to the Tailwind configuration:

```JS
'./vendor/lara-zeus/mark/resources/**/*.blade.php'
```

Then in your `AppServiceProvider`, add the following:

```PHP
use App\Models\User;
use LaraZeus\Mark\Facades\Mark;

Mark::markerModel(User::class)
```

Then, publish the migrations:

```bash
php artisan vendor:publish --tag=zeus-mark-migrations
```

Keep the migrations of the marks you want to use, also check the [customization](#customization) section in case you need it, then run:

```bash
php artisan migrate
```

Then add the related traits to the marker and markable models, the following table lists the available traits for each mark you want to use:

| Mark     | Marker       | Markable     |
|----------|--------------|--------------|
| Like     | HasLikes     | Likeable     |
| Rating   | HasRatings   | Ratable      |
| Bookmark | HasBookMarks | Bookmarkable |

---

## Usage

### Filament

#### Form Field

```PHP
use LaraZeus\Mark\Forms\Components\Mark;
use App\Models\Like;

Mark::make('like')

    // ready to use marks
    ->like()
    ->bookmark()
    ->rating()
    
    // custom marks
    ->icons([
        true => 'heroicon-o-hand-thumb-up',
        false => 'heroicon-o-hand-thumb-down',
    ])
    ->selectedIcons([
        true => 'heroicon-s-hand-thumb-up',
        false => 'heroicon-s-hand-thumb-down',
    ])
    
    // relationships
    ->relationship() // default uses component name
    ->relationship(name: 'like')
    ->relationship(metadata: fn($record) => ['name' => $record->name])
```

### Laravel

Check each Marker and markables traits for full list of available usages, the following is a sample:

```PHP
use LaraZeus\Mark\Forms\Components\Mark;
use App\Models\Like;

// Marker samples

// Note: metadata parameter is optional
$post = Post::first();

auth()->user()->like($post, metadata: []);
auth()->user()->dislike($post, metadata: []);
auth()->user()->hasLiked($post);
auth()->user()->markLike($post, value: true, metadata: []);
auth()->user()->unmarkLike($post);

// Markable samples

// Note: metadata parameter is optional
$user = auth()->user();
$post->likeBy($user, metadata: []);
$post->dislikeBy($user, metadata: []);
$post->isLikedBy($user);
$post->markLike($user, value: true, metadata: []);
$post->unmarkLike($user);
```

---

## Customization

In your `AppServiceProvider`, you can do the following:

```PHP
use App\Models\User;
use LaraZeus\Mark\Facades\Mark;

// in case you want to have your own pivot model, (must use the Mark trait)
Mark::likeMorphPivotModel(YourOwnLikeMorphPivotClass::class)
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Abdulmajeed-Jamaan](https://github.com/lara-zeus)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
