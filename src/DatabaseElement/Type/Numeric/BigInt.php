<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

class BigInt extends Integer
{

    public function __construct(string $name = 'bigint', ?string $length = null)
    {
        parent::__construct($name, $length);
    }
}