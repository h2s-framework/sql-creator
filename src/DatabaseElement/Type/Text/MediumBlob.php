<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class MediumBlob extends Blob
{

    public function __construct(string $name = 'mediumblob')
    {
        parent::__construct($name);
    }
}