<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

class Double extends Decimal
{
    public function __construct(string $name = 'double', ?string $length = null)
    {
        parent::__construct($name, $length);
    }
}