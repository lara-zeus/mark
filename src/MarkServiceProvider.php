<?php

namespace LaraZeus\Mark;

use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\Models\MarkLike;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MarkServiceProvider extends PackageServiceProvider
{
    public static string $name = 'zeus-mark';

    public static string $viewNamespace = 'zeus-mark';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews(static::$viewNamespace)
            ->discoversMigrations();
    }

    public function packageBooted(): void
    {
        Mark::markerModel(config('auth.providers.users.model'))
            ->likeMorphPivotModel(MarkLike::class);
    }
}
