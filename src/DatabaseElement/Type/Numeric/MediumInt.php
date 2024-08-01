<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

class MediumInt extends Integer
{
    public function __construct(string $name = 'mediumint', ?string $length = null)
    {
        parent::__construct($name, $length);
    }

}