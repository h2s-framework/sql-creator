<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

class SmallInt extends Integer
{
    public function __construct(string $name = 'smallint', ?string $length = null)
    {
        parent::__construct($name, $length);
    }

}