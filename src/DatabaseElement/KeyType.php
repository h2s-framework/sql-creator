<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 16.04.2019
 * Time: 23:22
 */

namespace Siarko\SqlCreator\DatabaseElement;


use Siarko\SqlCreator\DatabaseElement\Key\AbstractColumnKey;
use Siarko\SqlCreator\DatabaseElement\Key\Foreign;
use Siarko\SqlCreator\DatabaseElement\Key\Primary;

enum KeyType {

    case PRIMARY;
    case FOREIGN;

    public static function fromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if($name == $case->name){
                return $case;
            }
        }
        return null;
    }

    public function isKey(AbstractColumnKey $columnKey){
        switch ($this->name){
            case self::PRIMARY->name:{
                return $columnKey instanceof Primary;
            }
            case self::FOREIGN->name:{
                return $columnKey instanceof Foreign;
            }
        }
        return false;

    }
}