<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;

describe('scope', function () {
    beforeEach(function () {
        $this->marker1 = Marker::factory()->create();
        $this->markables1 = Markable::factory()
            ->count(3)
            ->create()
            ->each(
                fn (Markable $markable) => $markable
                    ->bookmarkedBy()
                    ->attach($this->marker1, ['value' => true])
            );

        $this->marker2 = Marker::factory()->create();
        $this->markables2 = Markable::factory()
            ->count(3)
            ->create()
            ->each(
                fn (Markable $markable) => $markable
                    ->bookmarkedBy()
                    ->attach($this->marker2, ['value' => true])
            );
    });

    describe('whereBookmarked', function () {
        test('has correct signature', function () {
            $method = (new ReflectionClass(Marker::class))
                ->getMethod('scopeWhereBookmarked');

            expect($method->isPublic())->toBeTrue();

            $parameters = $method->getParameters();
            expect($parameters)->toHaveCount(2);

            [$queryParam, $markableParam] = $parameters;

            expect($queryParam->getName())->toBe('query')
                ->and($queryParam->getType()->getName())->toBe(Builder::class)
                ->and($queryParam->getType()->allowsNull())->toBeFalse();

            expect($markableParam->getName())->toBe('markable')
                ->and($markableParam->getType())->toBeInstanceOf(ReflectionUnionType::class)
                ->and(collect($markableParam->getType()->getTypes())->map(fn ($t) => $t->getName())->all())
                ->toMatchArray([Model::class, Collection::class]);
        });

        test('filter the relations currectly', function () {
            expect(Marker::whereBookmarked($this->markables1)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker1);

            expect(Marker::whereBookmarked($this->markables2)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker2);

            expect(Marker::whereBookmarked($this->markables2)->get())
                ->toHaveCount(1)
                ->not->toContainModel($this->marker1);
        });
    });
});
