<?php

use Illuminate\Database\Eloquent\Collection;
use LaraZeus\Mark\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

use Illuminate\Database\Eloquent\Model;

expect()->extend('toContainModel', function (Model | Collection $model) {
    expect($this->value->modelKeys())->toContain(...Collection::wrap($model)->modelKeys());
});
