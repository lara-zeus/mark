<?php

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

    test('whereBookmarkedBy', function () {
        expect(Markable::whereBookmarkedBy($this->marker1)->get()->modelKeys())
            ->toHaveCount(3)
            ->toMatchArray($this->markables1->modelKeys())
            ->and(Markable::whereBookmarkedBy($this->marker2)->get()->modelKeys())
            ->toHaveCount(3)
            ->toMatchArray($this->markables2->modelKeys());
    });
});
