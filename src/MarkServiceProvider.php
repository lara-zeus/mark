<?php

namespace LaraZeus\Mark;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\Forms\Components\Mark as MarkFormComponent;
use LaraZeus\Mark\Models\MarkBookmark;
use LaraZeus\Mark\Models\MarkLike;
use LaraZeus\Mark\Models\MarkRate;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MarkServiceProvider extends PackageServiceProvider
{
    public static string $name = 'zeus-mark';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews(static::$name)
            ->discoversMigrations();
    }

    public function packageBooted(): void
    {
        Mark::likeMorphPivotModel(MarkLike::class)
            ->bookmarkMorphPivotModel(MarkBookmark::class)
            ->ratingMorphPivotModel(MarkRate::class);

        MarkFormComponent::configureUsing(function (MarkFormComponent $component) {
            $component->wrap();
        });

        FilamentAsset::register(
            [
                Css::make('mark-styles', __DIR__.'/../resources/dist/mark.css'),
            ],
            'lara-zeus/mark'
        );
    }
}
