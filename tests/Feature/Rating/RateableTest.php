<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use LaraZeus\Mark\Models\MarkRate;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;

describe('relation', function () {
    describe('ratedBy', function () {
        test('it has correct signature', function () {
            $marker = new Markable;
            expect(method_exists($marker, 'ratedBy'))->toBeTrue();
            expect($marker->ratedBy())->toBeInstanceOf(MorphToMany::class);
        });

        test('it can retrieve currect records via relation', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $mark = MarkRate::create([
                'value' => true,
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),

            ]);

            expect($markable->ratedBy()->get())
                ->toHaveCount(1)
                ->toContainModel($marker);
        })
            ->depends('it has correct signature');
    });

    describe('ratings', function () {
        test('it has correct signature', function () {
            $marker = new Markable;
            expect(method_exists($marker, 'ratings'))->toBeTrue();
            expect($marker->ratings())->toBeInstanceOf(MorphMany::class);
        });

        test('it can retrieve currect records via relation', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $mark = MarkRate::create([
                'value' => true,
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),

            ]);

            expect($markable->ratings()->get())
                ->toHaveCount(1)
                ->toContainModel($mark);
        })
            ->depends('it has correct signature');
    });

    describe('rating', function () {
        test('it has correct signature', function () {
            $marker = new Markable;
            expect(method_exists($marker, 'rating'))->toBeTrue();
            expect($marker->rating())->toBeInstanceOf(MorphOne::class);
        });

        test('it can retrieve currect records via relation', function () {
            $marker = Marker::factory()->create();
            $markable = Markable::factory()->create();

            $mark = MarkRate::create([
                'value' => true,
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),

            ]);

            expect($markable->rating()->get())
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
                    ->ratedBy()
                    ->attach($this->marker1, ['value' => true])
            );

        $this->marker2 = Marker::factory()->create();
        $this->markables2 = Markable::factory()
            ->count(3)
            ->create()
            ->each(
                fn (Markable $markable) => $markable
                    ->ratedBy()
                    ->attach($this->marker2, ['value' => true])
            );
    });

    describe('whereRatedBy', function () {

        test('has correct signature', function () {
            $method = (new ReflectionClass(Markable::class))
                ->getMethod('scopeWhereRatedBy');

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
            expect(Markable::whereRatedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables1);

            expect(Markable::whereRatedBy($this->marker2)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables2);

            expect(Markable::whereRatedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->not->toContainModel($this->markables2);
        });
    });
});
