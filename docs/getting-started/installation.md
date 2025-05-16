---
title: Installation
weight: 2
---

## Requirements

Mark requires the following to run:

- PHP 8.2+
- Laravel v11.40+
- Filament v3.0+

## Installation

Install @zeus Mark by running the following commands in your Laravel project directory.

```bash
composer require lara-zeus/mark
```

## Filament Theme
Then, set up a [Filament Custom Theme](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) and add the following path to the Tailwind configuration:

```js
'./vendor/lara-zeus/mark/resources/**/*.blade.php'
```

## Setup the Marker

to setup the authenticated user (the Marker) that the plugin depend on it:

In your `AppServiceProvider`, in the `boot` method add the following:

```PHP
use App\Models\User; // [tl! add]
use LaraZeus\Mark\Facades\Mark; // [tl! add]

Mark::markerModel(User::class); // [tl! focus]
```

## Migrations

to publish the migrations, run the command:

```bash
php artisan vendor:publish --tag=zeus-mark-migrations
```

Keep the migrations of the marks you want to use, also check the [customization](#customization) section in case you need it, then run:

```bash
php artisan migrate
```

## Set The Traits:

add the related traits to the marker and markable models, the following table lists the available traits for each mark you want to use:

| Mark     | Marker (user) | Markable (post) |
|----------|---------------|-----------------|
| Like     | HasLikes      | Likeable        |
| Rating   | HasRatings    | Ratable         |
| Bookmark | HasBookMarks  | Bookmarkable    |
