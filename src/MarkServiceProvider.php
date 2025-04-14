<?php

namespace LaraZeus\Mark;

use Illuminate\Filesystem\Filesystem;
use LaraZeus\Mark\Commands\MakeMarkMigrationCommand;
use LaraZeus\Mark\Commands\MakeMarkModelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MarkServiceProvider extends PackageServiceProvider
{
    public static string $name = 'zeus-mark';

    public static string $viewNamespace = 'zeus-mark';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasViews(static::$viewNamespace);
    }

    public function packageBooted(): void
    {
        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/zeus-mark/{$file->getFilename()}"),
                ], 'zeus-mark-stubs');
            }
        }
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            MakeMarkMigrationCommand::class,
            MakeMarkModelCommand::class,
        ];
    }
}
