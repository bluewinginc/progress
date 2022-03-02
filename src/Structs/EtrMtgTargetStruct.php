<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class EtrMtgTargetStruct
{
    public float $expectedChange = 0.0;
    public bool $met = false;
    public float $metPercent = 0.0;
    public bool $metPercent50 = false;
    public bool $metPercent67 = false;
    public float $value = 0.0;

    #[ArrayShape(['expectedChange' => "float", 'met' => "bool", 'metPercent' => "float", 'metPercent50' => "bool", 'metPercent67' => "bool", 'value' => "float"])]
    public function toArray() : array
    {
        return [
            'expectedChange' => $this->expectedChange,
            'met' => $this->met,
            'metPercent' => $this->metPercent,
            'metPercent50' => $this->metPercent50,
            'metPercent67' => $this->metPercent67,
            'value' => $this->value
        ];
    }
}