<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Numeric;

use JetBrains\PhpStorm\Pure;
use Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType;

class Integer extends AbstractDataType
{
    /**
     * @param string $name
     * @param string|null $length
     */
    #[Pure] public function __construct(string $name = 'int', ?string $length = "11")
    {
        parent::__construct($name, $length);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function cast(mixed $value): mixed
    {
        return intval($value);
    }

    public function getPhpType(): string
    {
        return 'int';
    }
}