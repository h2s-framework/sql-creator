<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\DateTime;

class Time extends \Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType
{

    public function __construct(string $name = 'time')
    {
        parent::__construct($name);
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }
}