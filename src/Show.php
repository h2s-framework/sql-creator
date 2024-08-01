<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 12.08.2018
 * Time: 00:45
 */

namespace Siarko\SqlCreator;

class Show extends Select {

    /**
     * @return string
     */
    public function parse(): string
    {
        $result = 'SHOW ';
        $result .= $this->arrayToList($this->selectedColumns);
        $result .= ' FROM '.$this->arrayToList($this->tables);
        return $result;
    }

}