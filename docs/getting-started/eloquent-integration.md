---
title: Eloquent Integration
weight: 4
---
you can use `Mark` as a form a standalone component, or integrate it with your laravel app

## Glossary

- **Mark**: The mark itself, e.g., like, bookmark, or rating.
- **Marker**: The entity that created the mark, e.g., a User.
- **Markable**: The entity that can be marked, e.g., a Post or Comment.

## Setup the Marker

to setup the authenticated user (the Marker) that the plugin depend on it:

In your `AppServiceProvider`, in the `boot` method add the following:

```PHP
use App\Models\User; // [tl! add]
use LaraZeus\Mark\Facades\Mark; // [tl! add]

Mark::markerModel(User::class) // [tl! focus]
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

Then add the related traits to the marker and markable models, the following table lists the available traits for each mark you want to use:

| Mark     | Marker       | Markable     |
|----------|--------------|--------------|
| Like     | HasLikes     | Likeable     |
| Rating   | HasRatings   | Ratable      |
| Bookmark | HasBookMarks | Bookmarkable |

---
