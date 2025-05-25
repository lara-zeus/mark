<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaraZeus\Mark\Models\MarkBookmark;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;

describe('indicator', function () {
    describe('hasBookmarked', function () {
        test('it has correct signature', function () {
            $reflection = new ReflectionClass(Marker::class);

            expect($reflection->hasMethod('hasBookmarked'))->toBeTrue();

            $method = $reflection->getMethod('hasBookmarked');

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

            $marker->bookmarks()->create([
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),
                'value' => true,
            ]);

            expect($marker->hasBookmarked($markable))->toBeTrue();
        })
            ->depends('it has correct signature');

        it('returns false if the marker has not marked the markable', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            expect($marker->hasBookmarked($markable))->toBeFalse();
        })
            ->depends('it has correct signature');
    });
});

describe('relation', function () {
    describe('bookmarks', function () {
        test('it has correct signature', function () {
            $marker = new Marker;
            expect(method_exists($marker, 'bookmarks'))->toBeTrue();
            expect($marker->bookmarks())->toBeInstanceOf(HasMany::class);
        });

        test('it can retrieve currect records via relation', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $mark = MarkBookmark::create([
                'value' => true,
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),

            ]);

            expect($marker->bookmarks()->get())
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
        })
            ->depends('has correct signature');
    });
});
