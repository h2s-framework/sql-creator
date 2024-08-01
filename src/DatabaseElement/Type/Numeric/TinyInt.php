<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

class TinyInt extends Integer
{
    public function __construct(string $name = 'tinyint', ?string $length = "1")
    {
        parent::__construct($name, $length);
    }

    public function cast(mixed $value): mixed
    {
        if(is_bool($value)){ return ($value ? 1 : 0);}
        return parent::cast($value);
    }


}