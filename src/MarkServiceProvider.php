<?php

namespace LaraZeus\Mark;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\Models\MarkBookmark;
use LaraZeus\Mark\Models\MarkLike;
use LaraZeus\Mark\Models\MarkRate;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MarkServiceProvider extends PackageServiceProvider
{
    public static string $name = 'lara-zeus-mark';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->discoversMigrations();
    }

    public function packageBooted(): void
    {
        Mark::likeMorphPivotModel(MarkLike::class)
            ->bookmarkMorphPivotModel(MarkBookmark::class)
            ->ratingMorphPivotModel(MarkRate::class);

        FilamentAsset::register([
            AlpineComponent::make('mark', __DIR__ . '/../resources/dist/mark.js'),
        ], package: static::$name);
    }
}
