<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 30.04.2019
 * Time: 16:59
 */

namespace Siarko\SqlCreator\Exceptions;

use Exception;

class BadSelectiveQueryException extends Exception {
    function __construct($message = 'no info') {
        parent::__construct($message);
    }
}