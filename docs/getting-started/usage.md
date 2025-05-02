---
title: Usage
weight: 3
---

# Filament

## Form Field
to use @zeus mark in your forms:

@blade
<x-auto-screenshot name="mark/mark-1" alt="using mark component in forms" />
@endblade

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

## Laravel

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