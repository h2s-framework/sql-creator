<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class Varchar extends Char
{

    public function __construct(string $name = 'varchar', ?string $length = "255")
    {
        parent::__construct($name, $length);
    }

}