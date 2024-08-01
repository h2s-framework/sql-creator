<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\DateTime;

class DateTime extends \Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType
{
    public function __construct(string $name = 'datetime')
    {
        parent::__construct($name);
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }
}