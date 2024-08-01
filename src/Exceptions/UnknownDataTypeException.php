<?php

namespace Siarko\SqlCreator\Exceptions;

use Throwable;

class UnknownDataTypeException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Unknown data type while trying to construct column type: ".$message, $code, $previous);
    }


}