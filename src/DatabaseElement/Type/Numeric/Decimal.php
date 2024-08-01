<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

use Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType;

class Decimal extends AbstractDataType
{

    public function __construct(string $name = 'decimal', ?string $length = null)
    {
        parent::__construct($name, $length);
    }

    public function cast(mixed $value): mixed
    {
        return doubleval($value);
    }

    public function getPhpType(): string
    {
        return 'double';
    }
}