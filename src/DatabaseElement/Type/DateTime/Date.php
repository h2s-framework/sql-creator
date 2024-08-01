<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\DateTime;

use Siarko\Serialization\Api\Attribute\Serializable;

class Date extends \Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType
{
    public function __construct(string $name = 'date')
    {
        parent::__construct($name);
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }
}