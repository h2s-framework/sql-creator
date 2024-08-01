<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\DateTime;

class TimeStamp extends \Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType
{

    public function __construct(string $name = 'timestamp')
    {
        parent::__construct($name);
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }

    public function getPhpType(): string
    {
        return 'string';
    }
}