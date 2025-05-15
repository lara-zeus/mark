<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;

$allScopes = [
    ['whereBookmarkedBy', Markable::class],
    ['whereBookmarked', Marker::class],
];

dataset('all_scopes', $allScopes);

dataset('marker_scopes', array_filter($allScopes, fn ($scope) => last($scope) === Marker::class));

dataset('markable_scopes', array_filter($allScopes, fn ($scope) => last($scope) === Markable::class));

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

test('has correct signature', function (string $scopeName, string $model) {
    $method = (new ReflectionClass($model))
        ->getMethod('scope' . ucfirst($scopeName));

    expect($method->isPublic())->toBeTrue();

    $parameters = $method->getParameters();
    expect($parameters)->toHaveCount(2);

    [$queryParam, $markableParam] = $parameters;

    expect($queryParam->getName())->toBe('query')
        ->and($queryParam->getType()->getName())->toBe(Builder::class)
        ->and($queryParam->getType()->allowsNull())->toBeFalse();

    expect($markableParam->getName())->toBe($model === Markable::class ? 'marker' : 'markable')
        ->and($markableParam->getType())->toBeInstanceOf(ReflectionUnionType::class)
        ->and(collect($markableParam->getType()->getTypes())->map(fn ($t) => $t->getName())->all())
        ->toMatchArray([Model::class, Collection::class]);
})
    ->with('all_scopes');

test('markable filter the relations currectly', function ($scopeName) {
    expect(Markable::{$scopeName}($this->marker1)->get())
        ->toHaveCount(3)
        ->toContainModel($this->markables1);

    expect(Markable::{$scopeName}($this->marker2)->get())
        ->toHaveCount(3)
        ->toContainModel($this->markables2);

    expect(Markable::{$scopeName}($this->marker1)->get())
        ->toHaveCount(3)
        ->not->toContainModel($this->markables2);
})
    ->with('markable_scopes');

test('marker filter the relations currectly', function ($scopeName) {
    expect(Marker::{$scopeName}($this->markables1)->get())
        ->toHaveCount(1)
        ->toContainModel($this->marker1);

    expect(Marker::{$scopeName}($this->markables2)->get())
        ->toHaveCount(1)
        ->toContainModel($this->marker2);

    expect(Marker::{$scopeName}($this->markables2)->get())
        ->toHaveCount(1)
        ->not->toContainModel($this->marker1);
})
    ->with('marker_scopes');
