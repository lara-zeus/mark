# This is my package mark

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lara-zeus/mark.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/mark)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/lara-zeus/mark/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/lara-zeus/mark/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/lara-zeus/mark/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/lara-zeus/mark/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lara-zeus/mark.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/mark)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require lara-zeus/mark
```
Create marks using the command:

```bash
 php artisan mark:make Like
```

In AppServiceProvider set the following:

```PHP
use App\Models\Comment;use App\Models\Like as GeneratedMarkModel;use App\Models\Post;use LaraZeus\Mark\Mark as MarkFacade;

MarkFacade::markerModel(User::class)
    ->addRelations(Comment::class, [
        GeneratedMarkModel::class
    ])
    // you can chain more types of relations
    ->addRelations(Post::class, [
        GeneratedMarkModel::class,
    ]);
```

Then, in your filament resource use it as the following:

```PHP
use LaraZeus\Mark\Forms\Components\Mark;
use App\Models\Like;

Mark::make(Like::class)
    ->isLike()
    // Or ->isBookmark() or ->isRating()
```

Then, setup a filament [custom theme](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) and add the following path to the tailwind paths:

```
'./vendor/lara-zeus/mark/resources/**/*.blade.php',
```

Optionally, you can list all automatically generated relations using:

```bash
php artisan mark:list
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="mark-views"
```


## Usage



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
