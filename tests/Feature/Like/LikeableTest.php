<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;

describe('scope', function () {
    describe('whereLikedOrDislikedBy', function () {
        beforeEach(function () {
            $this->marker1 = Marker::factory()->create();
            $this->markables1 = Markable::factory()
                ->count(3)
                ->create()
                ->each(
                    fn (Markable $markable) => $markable
                        ->likedBy()
                        ->attach($this->marker1, ['value' => true])
                );

            $this->marker2 = Marker::factory()->create();
            $this->markables2 = Markable::factory()
                ->count(3)
                ->create()
                ->each(
                    fn (Markable $markable) => $markable
                        ->likedBy()
                        ->attach($this->marker2, ['value' => true])
                );
        });

        test('has correct signature', function () {
            $method = (new ReflectionClass(Markable::class))
                ->getMethod('scopeWhereLikedOrDislikedBy');

            expect($method->isPublic())->toBeTrue();

            $parameters = $method->getParameters();
            expect($parameters)->toHaveCount(2);

            [$queryParam, $markableParam] = $parameters;

            expect($queryParam->getName())->toBe('query')
                ->and($queryParam->getType()->getName())->toBe(Builder::class)
                ->and($queryParam->getType()->allowsNull())->toBeFalse();

            expect($markableParam->getName())->toBe('marker')
                ->and($markableParam->getType())->toBeInstanceOf(ReflectionUnionType::class)
                ->and(collect($markableParam->getType()->getTypes())->map(fn ($t) => $t->getName())->all())
                ->toMatchArray([Model::class, Collection::class]);
        });

        test('filter the relations currectly', function () {
            expect(Markable::whereLikedOrDislikedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables1);

            expect(Markable::whereLikedOrDislikedBy($this->marker2)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables2);

            expect(Markable::whereLikedOrDislikedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->not->toContainModel($this->markables2);
        });
    });

    describe('whereLikedBy', function () {
        beforeEach(function () {
            $this->marker1 = Marker::factory()->create();
            $this->markables1 = Markable::factory()
                ->count(3)
                ->create()
                ->each(
                    fn (Markable $markable) => $markable
                        ->likedBy()
                        ->attach($this->marker1, ['value' => true])
                );

            $this->marker2 = Marker::factory()->create();
            $this->markables2 = Markable::factory()
                ->count(3)
                ->create()
                ->each(
                    fn (Markable $markable) => $markable
                        ->likedBy()
                        ->attach($this->marker2, ['value' => true])
                );
        });

        test('has correct signature', function () {
            $method = (new ReflectionClass(Markable::class))
                ->getMethod('scopeWhereLikedBy');

            expect($method->isPublic())->toBeTrue();

            $parameters = $method->getParameters();
            expect($parameters)->toHaveCount(2);

            [$queryParam, $markableParam] = $parameters;

            expect($queryParam->getName())->toBe('query')
                ->and($queryParam->getType()->getName())->toBe(Builder::class)
                ->and($queryParam->getType()->allowsNull())->toBeFalse();

            expect($markableParam->getName())->toBe('marker')
                ->and($markableParam->getType())->toBeInstanceOf(ReflectionUnionType::class)
                ->and(collect($markableParam->getType()->getTypes())->map(fn ($t) => $t->getName())->all())
                ->toMatchArray([Model::class, Collection::class]);
        });

        test('filter the relations currectly', function () {
            expect(Markable::whereLikedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables1);

            expect(Markable::whereLikedBy($this->marker2)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables2);

            expect(Markable::whereLikedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->not->toContainModel($this->markables2);
        });
    });

    describe('whereDislikedBy', function () {
        beforeEach(function () {
            $this->marker1 = Marker::factory()->create();
            $this->markables1 = Markable::factory()
                ->count(3)
                ->create()
                ->each(
                    fn (Markable $markable) => $markable
                        ->likedBy()
                        ->attach($this->marker1, ['value' => false])
                );

            $this->marker2 = Marker::factory()->create();
            $this->markables2 = Markable::factory()
                ->count(3)
                ->create()
                ->each(
                    fn (Markable $markable) => $markable
                        ->likedBy()
                        ->attach($this->marker2, ['value' => false])
                );
        });

        test('has correct signature', function () {
            $method = (new ReflectionClass(Markable::class))
                ->getMethod('scopeWhereDislikedBy');

            expect($method->isPublic())->toBeTrue();

            $parameters = $method->getParameters();
            expect($parameters)->toHaveCount(2);

            [$queryParam, $markableParam] = $parameters;

            expect($queryParam->getName())->toBe('query')
                ->and($queryParam->getType()->getName())->toBe(Builder::class)
                ->and($queryParam->getType()->allowsNull())->toBeFalse();

            expect($markableParam->getName())->toBe('marker')
                ->and($markableParam->getType())->toBeInstanceOf(ReflectionUnionType::class)
                ->and(collect($markableParam->getType()->getTypes())->map(fn ($t) => $t->getName())->all())
                ->toMatchArray([Model::class, Collection::class]);
        });

        test('filter the relations currectly', function () {
            expect(Markable::whereDislikedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables1);

            expect(Markable::whereDislikedBy($this->marker2)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables2);

            expect(Markable::whereDislikedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->not->toContainModel($this->markables2);
        });
    });
});
