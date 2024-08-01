<?php

namespace Siarko\SqlCreator\Language;

class BoundParam
{
    public function __construct(private string $name)
    {
    }

    public function __toString(): string
    {
        return $this->name;
    }


}