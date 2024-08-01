<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class Blob extends Char
{

    public function __construct(string $name = 'blob', ?string $length = null)
    {
        parent::__construct($name, $length);
    }

}