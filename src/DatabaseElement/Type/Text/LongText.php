<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class LongText extends Text
{
    public function __construct(string $name = 'longtext')
    {
        parent::__construct($name);
    }
}