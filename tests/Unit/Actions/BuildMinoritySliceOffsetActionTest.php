<?php

declare(strict_types=1);

namespace Modules\Chart\Tests\Unit\Actions;

use Modules\Chart\Actions\Chart\BuildMinoritySliceOffsetAction;

it('offsets only the index of the smallest value', function (): void {
    $result = app(BuildMinoritySliceOffsetAction::class)->execute([70, 6, 24]);

    expect($result)->toBe([0, 40, 0]);
});

it('offsets the first occurrence when values are tied for the minimum', function (): void {
    $result = app(BuildMinoritySliceOffsetAction::class)->execute([5, 5, 90]);

    expect($result)->toBe([40, 0, 0]);
});

it('returns an empty array for an empty input', function (): void {
    $result = app(BuildMinoritySliceOffsetAction::class)->execute([]);

    expect($result)->toBe([]);
});

it('treats non-numeric values as zero', function (): void {
    $result = app(BuildMinoritySliceOffsetAction::class)->execute([50, 'n/a', 30]);

    expect($result)->toBe([0, 40, 0]);
});

it('offsets the single slice when only one value is given', function (): void {
    $result = app(BuildMinoritySliceOffsetAction::class)->execute([100]);

    expect($result)->toBe([40]);
});
