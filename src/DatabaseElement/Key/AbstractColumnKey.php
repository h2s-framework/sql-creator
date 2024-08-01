<?php

namespace Siarko\SqlCreator\DatabaseElement\Key;

use Siarko\Serialization\Api\Attribute\Serializable;

abstract class AbstractColumnKey
{

    public function __construct(#[Serializable] protected string $columnName)
    {
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getKeyName(): string
    {
        return '';
    }

    public abstract function matches(AbstractColumnKey $key): bool;
}