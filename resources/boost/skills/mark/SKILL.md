---
name: zeus-mark
description: Use when installing, configuring, or using lara-zeus/mark for Laravel likes, bookmarks, ratings, or the Filament Mark form component.
---

# Lara Zeus Mark

## Overview & Requirements
`lara-zeus/mark` provides ready-to-use likes, bookmarks, ratings, and custom marks for Laravel Eloquent models along with a Filament form component.
- **PHP**: `^8.4`
- **Filament**: `^5.0`
- **Package Name**: `lara-zeus/mark` (Service Provider: `LaraZeus\Mark\MarkServiceProvider`, view/migration tag prefix: `zeus-mark`)

## Installation
```bash
composer require lara-zeus/mark
```

Add the view source path to your Filament custom theme CSS file:
```css
@source '../../../../vendor/lara-zeus/mark/resources/views/**/*.blade.php';
```

## Configuration & Migrations
1. Register the marker model in `AppServiceProvider::boot()`:
```php
use App\Models\User;
use LaraZeus\Mark\Facades\Mark;

Mark::markerModel(User::class);
```
2. Publish and run the migrations (keep only the mark migrations you need: `create_mark_like_table`, `create_mark_bookmark_table`, `create_mark_rating_table`):
```bash
php artisan vendor:publish --tag=zeus-mark-migrations
php artisan migrate
```
3. Add the matching traits to your Marker (`User`) and Markable (e.g. `Post`) models:
- **Like**: `LaraZeus\Mark\Traits\Like\HasLikes` on Marker, `LaraZeus\Mark\Traits\Like\Likeable` on Markable
- **Bookmark**: `LaraZeus\Mark\Traits\Bookmark\HasBookmarks` on Marker, `LaraZeus\Mark\Traits\Bookmark\Bookmarkable` on Markable
- **Rating**: `LaraZeus\Mark\Traits\Rating\HasRatings` on Marker, `LaraZeus\Mark\Traits\Rating\Rateable` on Markable

## Filament Panel Registration
No panel plugin registration is required. Use `LaraZeus\Mark\Forms\Components\Mark` directly inside Filament form schemas.

## Core Usage & Examples

### Filament Form Field
```php
use LaraZeus\Mark\Forms\Components\Mark;

// Built-in presets: ->like(), ->bookmark(), or ->rating()
Mark::make('like')
    ->like()
    ->relationship() // or ->relationship(name: 'like', metadata: fn ($record) => ['name' => $record->name])
    ->colors([
        true => 'success',
        false => 'danger',
    ]);

// Custom icons & sequential rating
Mark::make('rating')
    ->icons(array_fill(1, 5, 'heroicon-o-star'))
    ->selectedIcons(array_fill(1, 5, 'heroicon-s-star'))
    ->sequential()
    ->relationship();
```

### Eloquent API
```php
// From Marker (User)
auth()->user()->like($post, metadata: []);
auth()->user()->dislike($post, metadata: []);
auth()->user()->hasLiked($post);
auth()->user()->markLike($post, value: true, metadata: []);
auth()->user()->unmarkLike($post);

// Bookmark & Rating equivalents:
// bookmark(), unmarkBookmark(), hasBookmarked(), rate(), unmarkRating(), hasRated()

// From Markable (Post)
$post->likeBy($user, metadata: []);
$post->dislikeBy($user, metadata: []);
$post->isLikedBy($user);
$post->unmarkLike($user);
```

### Custom Morph Pivot Models
```php
use LaraZeus\Mark\Facades\Mark;

Mark::likeMorphPivotModel(CustomLikePivot::class);
// Also available: bookmarkMorphPivotModel(), ratingMorphPivotModel()
```

## Common Pitfalls / Anti-Patterns
- Forgetting `Mark::markerModel(User::class)` in `AppServiceProvider::boot()` causes `RuntimeException: Marker Model is not set.`.
- `HasMarkRelations::relationship()` requires an authenticated `Illuminate\Database\Eloquent\Model` user and a `MorphOne` or `MorphMany` relation on the record.
- `->relationship()` automatically sets `->dehydrated(false)` and handles persistence via `saveRelationshipsUsing()`.
