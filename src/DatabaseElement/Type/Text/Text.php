<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class Text extends Char
{

    public function __construct(string $name = 'text', ?string $length = null)
    {
        parent::__construct($name, $length);
    }
}