<?php

namespace Bluewing\Progress\Structs;

class EtrMtgTargetStruct
{
    public float $expectedChange = 0.0;
    public bool $met = false;
    public float $metPercent = 0.0;
    public bool $metPercent50 = false;
    public bool $metPercent67 = false;
    public float $value = 0.0;
}