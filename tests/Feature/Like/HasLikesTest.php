<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaraZeus\Mark\Models\MarkLike;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;

describe('indicator', function () {
    describe('hasLiked', function () {
        test('it has correct signature', function () {
            $reflection = new ReflectionClass(Marker::class);

            expect($reflection->hasMethod('hasLiked'))->toBeTrue();

            $method = $reflection->getMethod('hasLiked');

            $parameters = $method->getParameters();
            expect(count($parameters))->toBe(1);

            $param = $parameters[0];
            $paramType = $param->getType();

            expect($paramType)->not->toBeNull();
            expect($paramType->getName())->toBe(Model::class);
            expect($paramType->isBuiltin())->toBeFalse();

            $returnType = $method->getReturnType();

            expect($returnType)->not->toBeNull();
            expect($returnType->getName())->toBe('bool');
            expect($returnType->allowsNull())->toBeFalse();
        });

        it('returns true if the marker has marked the markable', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $marker->likes()->create([
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),
                'value' => true,
            ]);

            expect($marker->hasLiked($markable))->toBeTrue();
        })
            ->depends('it has correct signature');

        it('returns false if the marker has not marked the markable', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            expect($marker->hasLiked($markable))->toBeFalse();
        })
            ->depends('it has correct signature');
    });
});

describe('relation', function () {
    describe('likes', function () {
        test('it has correct signature', function () {
            $marker = new Marker;
            expect(method_exists($marker, 'likes'))->toBeTrue();
            expect($marker->likes())->toBeInstanceOf(HasMany::class);
        });

        test('it can retrieve currect records via relation', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $mark = MarkLike::create([
                'value' => true,
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),

            ]);

            expect($marker->likes()->get())
                ->toHaveCount(1)
                ->toContainModel($mark);
        })
            ->depends('it has correct signature');
    });
});

describe('scope', function () {
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

    describe('whereLikedOrDisliked', function () {
        test('has correct signature', function () {
            $method = (new ReflectionClass(Marker::class))
                ->getMethod('scopeWhereLikedOrDisliked');

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
            expect(Marker::whereLikedOrDisliked($this->markables1)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker1);

            expect(Marker::whereLikedOrDisliked($this->markables2)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker2);

            expect(Marker::whereLikedOrDisliked($this->markables2)->get())
                ->toHaveCount(1)
                ->not->toContainModel($this->marker1);
        });
    });

    describe('whereLiked', function () {
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
            $method = (new ReflectionClass(Marker::class))
                ->getMethod('scopeWhereLiked');

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
            expect(Marker::whereLiked($this->markables1)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker1);

            expect(Marker::whereLiked($this->markables2)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker2);

            expect(Marker::whereLiked($this->markables2)->get())
                ->toHaveCount(1)
                ->not->toContainModel($this->marker1);
        });
    });

    describe('whereDisliked', function () {
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
            $method = (new ReflectionClass(Marker::class))
                ->getMethod('scopeWhereDisliked');

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
            expect(Marker::whereDisliked($this->markables1)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker1);

            expect(Marker::whereDisliked($this->markables2)->get())
                ->toHaveCount(1)
                ->toContainModel($this->marker2);

            expect(Marker::whereDisliked($this->markables2)->get())
                ->toHaveCount(1)
                ->not->toContainModel($this->marker1);
        });
    });
});
