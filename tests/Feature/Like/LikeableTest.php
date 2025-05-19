<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use LaraZeus\Mark\Models\MarkLike;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;

describe('indicator', function () {
    describe('isLikedBy', function () {
        test('it has correct signature', function () {
            $reflection = new ReflectionClass(Markable::class);

            expect($reflection->hasMethod('isLikedBy'))->toBeTrue();

            $method = $reflection->getMethod('isLikedBy');

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

        test('it returns true if the marker has marked the markable', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $markable->likes()->create([
                'marker_id' => $marker->getKey(),
                'value' => true,
            ]);

            expect($markable->isLikedBy($marker))->toBeTrue();
        })
            ->depends('it has correct signature');

        test('it returns false if the marker has not marked the markable', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            expect($markable->isLikedBy($marker))->toBeFalse();
        })
            ->depends('it has correct signature');
    });

    describe('isDislikedBy', function () {
        test('it has correct signature', function () {
            $reflection = new ReflectionClass(Markable::class);

            expect($reflection->hasMethod('isDislikedBy'))->toBeTrue();

            $method = $reflection->getMethod('isDislikedBy');

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

        test('it returns true if the marker has marked the markable', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $markable->likes()->create([
                'marker_id' => $marker->getKey(),
                'value' => false,
            ]);

            expect($markable->isDislikedBy($marker))->toBeTrue();
        })
            ->depends('it has correct signature');

        test('it returns false if the marker has not marked the markable', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            expect($markable->isDislikedBy($marker))->toBeFalse();
        })
            ->depends('it has correct signature');
    });
});

describe('relation', function () {
    describe('likedBy', function () {
        test('it has correct signature', function () {
            $marker = new Markable;
            expect(method_exists($marker, 'likedBy'))->toBeTrue();
            expect($marker->likedBy())->toBeInstanceOf(MorphToMany::class);
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

            expect($markable->likedBy()->get())
                ->toHaveCount(1)
                ->toContainModel($marker);
        })
            ->depends('it has correct signature');
    });

    describe('likes', function () {
        test('it has correct signature', function () {
            $marker = new Markable;
            expect(method_exists($marker, 'likes'))->toBeTrue();
            expect($marker->likes())->toBeInstanceOf(MorphMany::class);
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

            expect($markable->likes()->get())
                ->toHaveCount(1)
                ->toContainModel($mark);
        })
            ->depends('it has correct signature');
    });

    describe('like', function () {
        test('it has correct signature', function () {
            $marker = new Markable;
            expect(method_exists($marker, 'like'))->toBeTrue();
            expect($marker->like())->toBeInstanceOf(MorphOne::class);
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

            expect($markable->like()->get())
                ->toHaveCount(1)
                ->toContainModel($mark);
        })
            ->depends('it has correct signature');
    });
});

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
