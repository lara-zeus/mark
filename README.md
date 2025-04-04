<p align="center">
<a href="https://larazeus.com"><img src="https://larazeus.com/images/lara-zeus-mark.webp?v=3" /></a>
</p>

## A ready-to-use [Marking](#Glossary) feature for Laravel, with built-in addons for Filament.

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
- Easily create a mark with a single command.
- Automatically register relationships.
- Ready-to-use Filament form components.
- Fully customizable and extensible.

## Demo

> Visit our demo site: https://demo.larazeus.com/admin/components-demo/mark


## Full Documentation

> Visit our website to get the complete documentation: https://larazeus.com/docs/mark

## Installation

```bash
composer require lara-zeus/mark
```

Then, set up a Filament [custom theme](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) and add the following path to the Tailwind configuration:

```JS
'./vendor/lara-zeus/mark/resources/**/*.blade.php'
```

In your `AppServiceProvider`, add the following:

```PHP
use App\Models\User;
use LaraZeus\Mark\Facades\Mark;

Mark::markerModel(User::class)
```

## Usage

### Creating a Mark

Create a new [Mark](#Glossary) using the following command:

```bash
php artisan mark:make Like
```

### Relations Registration

#### Automatic

In your `AppServiceProvider` add the following:

```PHP
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Bookmark;
use LaraZeus\Mark\Facades\Mark;

Mark::addRelations(
    markable: Post::class,
    marks: [
        Like::class,
        Bookmark::class
    ]
)->addRelations(
    markable: Comment::class,
    marks: [
        Like::class,
    ]
)
```

Then you can list the relations using the command:

```bash
php artisan mark:list
```

#### Manual

You need to manually create relationship methods using [laravel polymorphic relationships](https://laravel.com/docs/12.x/eloquent-relationships#polymorphic-relationships) for the [Marker](#Glossary) and the [Markable](#Glossary).

### Filament

In your Filament resource, you can use it as follows:

```PHP
use LaraZeus\Mark\Forms\Components\Mark;
use App\Models\Like;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Mark::make('like')
                ->relationship() // By default, it will use the name of the component as the Mark relation name.
                ->like(),
                
            Mark::make('anyName')
                ->relationship('like') // Optional pass the Mark relation name
                ->like(),
                
            Mark::make('anyName')
                ->relationship(Like::class) // Optional pass the Mark model (works only with automatic relation registration)
                ->like()
        ]);
}
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="mark-views"
```
---

## Glossary

- **Mark**: The mark itself, e.g., like, bookmark, or rating.
- **Marker**: The entity that created the mark, e.g., a User.
- **Markable**: The entity that can be marked, e.g., a Post or Comment.

---

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
