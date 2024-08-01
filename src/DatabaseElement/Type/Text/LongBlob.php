<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class LongBlob extends Blob
{
    public function __construct(string $name = 'longblob')
    {
        parent::__construct($name);
    }

}