<?php

namespace Bluewing\Progress\Structs;

class EtrTargetStruct
{
    /** @var float $expectedChange */
    public $expectedChange = 0.0;

    /** @var bool $met */
    public $met = false;

    /** @var float $metPercent */
    public $metPercent = 0.0;

    /** @var bool $metPercent50 */
    public $metPercent50 = false;

    /** @var bool $metPercent67 */
    public $metPercent67 = false;

    /** @var float $value */
    public $value = 0.0;
}