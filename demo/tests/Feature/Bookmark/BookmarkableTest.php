<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use LaraZeus\Mark\Models\MarkBookmark;

describe('indicator', function () {
    describe('isBookmarkedBy', function () {
        test('it has correct signature', function () {
            $reflection = new ReflectionClass(Comment::class);

            expect($reflection->hasMethod('isBookmarkedBy'))->toBeTrue();

            $method = $reflection->getMethod('isBookmarkedBy');

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
            $marker = User::factory()->create();
            $markable = Comment::factory()->create();

            $markable->bookmarks()->create([
                'marker_id' => $marker->getKey(),
            ]);

            expect($markable->isBookmarkedBy($marker))->toBeTrue();
        })
            ->depends('it has correct signature');

        test('it returns false if the marker has not marked the markable', function () {
            $marker = User::factory()->create();
            $markable = Comment::factory()->create();

            expect($markable->isBookmarkedBy($marker))->toBeFalse();
        })
            ->depends('it has correct signature');
    });
});

describe('relation', function () {
    describe('bookmarkedBy', function () {
        test('it has correct signature', function () {
            $marker = new Comment;
            expect(method_exists($marker, 'bookmarkedBy'))->toBeTrue();
            expect($marker->bookmarkedBy())->toBeInstanceOf(MorphToMany::class);
        });

        test('it can retrieve correct records via relation', function () {
            $marker = User::factory()->create();
            $markable = Comment::factory()->create();

            $mark = MarkBookmark::create([
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),
            ]);

            expect($markable->bookmarkedBy()->get())
                ->toHaveCount(1)
                ->toContainModel($marker);
        })
            ->depends('it has correct signature');
    });

    describe('bookmarks', function () {
        test('it has correct signature', function () {
            $marker = new Comment;
            expect(method_exists($marker, 'bookmarks'))->toBeTrue();
            expect($marker->bookmarks())->toBeInstanceOf(MorphMany::class);
        });

        test('it can retrieve correct records via relation', function () {
            $marker = User::factory()->create();
            $markable = Comment::factory()->create();

            $mark = MarkBookmark::create([
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),

            ]);

            expect($markable->bookmarks()->get())
                ->toHaveCount(1)
                ->toContainModel($mark);
        })
            ->depends('it has correct signature');
    });

    describe('bookmark', function () {
        test('it has correct signature', function () {
            $marker = new Comment;
            expect(method_exists($marker, 'bookmark'))->toBeTrue();
            expect($marker->bookmark())->toBeInstanceOf(MorphOne::class);
        });

        test('it can retrieve correct records via relation', function () {
            $marker = User::factory()->create();
            $markable = Comment::factory()->create();

            $mark = MarkBookmark::create([
                'marker_id' => $marker->getKey(),
                'markable_id' => $markable->getKey(),
                'markable_type' => $markable->getMorphClass(),

            ]);

            expect($markable->bookmark()->get())
                ->toHaveCount(1)
                ->toContainModel($mark);
        })
            ->depends('it has correct signature');
    });
});

describe('scope', function () {
    beforeEach(function () {
        $this->marker1 = User::factory()->create();
        $this->markables1 = Comment::factory()
            ->count(3)
            ->create()
            ->each(
                fn (Comment $markable) => $markable
                    ->bookmarkedBy()
                    ->attach($this->marker1)
            );

        $this->marker2 = User::factory()->create();
        $this->markables2 = Comment::factory()
            ->count(3)
            ->create()
            ->each(
                fn (Comment $markable) => $markable
                    ->bookmarkedBy()
                    ->attach($this->marker2)
            );
    });

    describe('whereBookmarkedBy', function () {

        test('has correct signature', function () {
            $method = (new ReflectionClass(Comment::class))
                ->getMethod('scopeWhereBookmarkedBy');

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

        test('filter the relations correctly', function () {
            expect(Comment::whereBookmarkedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables1);

            expect(Comment::whereBookmarkedBy($this->marker2)->get())
                ->toHaveCount(3)
                ->toContainModel($this->markables2);

            expect(Comment::whereBookmarkedBy($this->marker1)->get())
                ->toHaveCount(3)
                ->not->toContainModel($this->markables2);
        });
    });
});
