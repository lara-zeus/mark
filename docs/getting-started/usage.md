---
title: Usage
weight: 3
---

# Filament

## Form Field

to use @zeus Mark in your forms:

```PHP
use LaraZeus\Mark\Forms\Components\Mark;
use App\Models\Like;

Mark::make('like') // [tl! focus]
```

and you can chain any other method as in filament default components:

```PHP
Mark::make('like')
    ->label('Like it')
    ->columnFullSpan()
    // ...
```

## Mark Type:

to set the marker type:

```PHP
Mark::make('like')
    ->like() // [tl! focus]
    ->bookmark() // [tl! focus]
    ->rating() // [tl! focus]
```

## custom mark

```PHP
Mark::make('like')
    ->icons([ // [tl! focus]
        true => 'heroicon-o-hand-thumb-up', // [tl! focus]
        false => 'heroicon-o-hand-thumb-down', // [tl! focus]
    ]) // [tl! focus]
    ->selectedIcons([ // [tl! focus]
        true => 'heroicon-s-hand-thumb-up', // [tl! focus]
        false => 'heroicon-s-hand-thumb-down', // [tl! focus]
    ]) // [tl! focus]
```

## Set The Relationships

```php
Mark::make('like')
    // default uses component name
    ->relationship() // [tl! focus]
    // or custom relation name
    ->relationship(name: 'like') // [tl! focus]
```

## Set Custom Metadata

```php
Mark::make('like')
    ->relationship(metadata: fn($record) => ['name' => $record->name]) // [tl! focus]
```
