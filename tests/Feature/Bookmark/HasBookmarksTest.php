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

    test('whereHasBookmarked', function () {
        expect(Marker::whereHasBookmarked($this->markables1)->get()->modelKeys())
            ->toHaveCount(1)
            ->toMatchArray([$this->marker1->getKey()])
            ->and(Marker::whereHasBookmarked($this->markables2)->get()->modelKeys())
            ->toHaveCount(1)
            ->toMatchArray([$this->marker2->getKey()]);

    });
});
