<?php

namespace Larazeus\Mark;

use Illuminate\Filesystem\Filesystem;
use Larazeus\Mark\Commands\MarkListRelationsCommand;
use Larazeus\Mark\Commands\MarkMakeMigrationCommand;
use Larazeus\Mark\Commands\MarkMakeModelCommand;
use Larazeus\Mark\Testing\TestsMark;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MarkServiceProvider extends PackageServiceProvider
{
    public static string $name = 'mark';

    public static string $viewNamespace = 'mark';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->askToStarRepoOnGitHub('lara-zeus/mark');
            });

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/mark/{$file->getFilename()}"),
                ], 'mark-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsMark);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'lara-zeus/mark';
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            MarkListRelationsCommand::class,
            MarkMakeMigrationCommand::class,
            MarkMakeModelCommand::class,
        ];
    }
}
