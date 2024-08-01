<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class MediumText extends Text
{

    public function __construct(string $name = 'mediumtext')
    {
        parent::__construct($name);
    }

}