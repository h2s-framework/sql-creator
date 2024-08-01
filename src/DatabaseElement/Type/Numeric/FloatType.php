<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

use Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType;

class FloatType extends AbstractDataType
{
    public function __construct(string $name = 'float', ?string $length = null)
    {
        parent::__construct($name, $length);
    }

    public function cast(mixed $value): mixed
    {
        return floatval($value);
    }

    public function getPhpType(): string
    {
        return 'float';
    }
}