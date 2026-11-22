<?php

use LaraZeus\Mark\Models\MarkLike;
use LaraZeus\Mark\Models\MarkRate;
use LaraZeus\Mark\Tests\Fixtures\MarkFormComponent;
use LaraZeus\Mark\Tests\Fixtures\MarkRelationshipFormComponent;
use LaraZeus\Mark\Tests\Models\Markable;
use LaraZeus\Mark\Tests\Models\Marker;
use Livewire\Livewire;

it('renders mark form components with defaults and closure options', function () {
    Livewire::test(MarkFormComponent::class)
        ->assertSet('data.rating', 3)
        ->assertSet('data.like', null)
        ->assertSet('data.bookmark', null)
        ->assertSeeHtml('String(state)')
        ->assertSeeHtml('disabled')
        ->assertOk();
});

it('can set and validate mark form component values', function () {
    Livewire::test(MarkFormComponent::class)
        ->set('data.like', 1)
        ->set('data.bookmark', 1)
        ->set('data.rating', 5)
        ->set('data.custom', 'a')
        ->call('save')
        ->assertHasNoErrors();
});

it('validates allowed mark values using in rule', function () {
    Livewire::test(MarkFormComponent::class)
        ->set('data.rating', 99)
        ->call('save')
        ->assertHasErrors(['data.rating' => 'in']);
});

it('loads, saves, and deletes mark relationships', function () {
    $marker = Marker::factory()->create();
    $markable = Markable::factory()->create();

    $this->actingAs($marker);

    Livewire::test(MarkRelationshipFormComponent::class, ['record' => $markable])
        ->assertSet('data.likes', null)
        ->assertSet('data.ratings', null)
        ->set('data.likes', 1)
        ->set('data.ratings', 4)
        ->call('save')
        ->assertHasNoErrors();

    expect(MarkLike::query()->where('marker_id', $marker->getKey())->value('value'))->toBe('1')
        ->and(MarkRate::query()->where('marker_id', $marker->getKey())->value('value'))->toBe('4')
        ->and(MarkRate::query()->where('marker_id', $marker->getKey())->value('metadata'))->toBe(['source' => 'form']);

    Livewire::test(MarkRelationshipFormComponent::class, ['record' => $markable])
        ->assertSet('data.likes', '1')
        ->assertSet('data.ratings', '4')
        ->set('data.likes', null)
        ->call('save')
        ->assertHasNoErrors();

    expect(MarkLike::query()->where('marker_id', $marker->getKey())->exists())->toBeFalse()
        ->and(MarkRate::query()->where('marker_id', $marker->getKey())->exists())->toBeTrue();
});
