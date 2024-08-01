<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\DateTime;

class Year extends \Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType
{

    public function __construct(string $name = 'year')
    {
        parent::__construct($name);
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }
}