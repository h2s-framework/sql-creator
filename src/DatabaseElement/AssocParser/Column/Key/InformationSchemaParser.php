<?php

namespace Siarko\SqlCreator\DatabaseElement\AssocParser\Column\Key;

use Siarko\SqlCreator\DatabaseElement\Key\Foreign;
use Siarko\SqlCreator\DatabaseElement\Key\Primary;
use Siarko\SqlCreator\DatabaseElement\KeyType;
use Siarko\SqlCreator\DatabaseElement\Key\AbstractColumnKey;

class InformationSchemaParser implements ColumnKeyParserInterface
{

    public function parse(array $data): AbstractColumnKey
    {
        if($data[self::COLUMN_KEY_TYPE] == KeyType::PRIMARY->name){
            $key = new Primary($data[self::COLUMN_KEY_FK_COLUMN_SOURCE]);
        }else{
            $key = new Foreign($data[self::COLUMN_KEY_FK_COLUMN_SOURCE]);
            $key->setConstraintName($data[self::COLUMN_KEY_TYPE]);
            $key->setSourceTable($data[self::COLUMN_KEY_FK_TABLE_SOURCE]);
            $key->setSourceColumn($data[self::COLUMN_KEY_FK_COLUMN_SOURCE]);
            $key->setTargetTable($data[self::COLUMN_KEY_FK_TABLE_TARGET]);
            $key->setTargetColumn($data[self::COLUMN_KEY_FK_COLUMN_TARGET]);
        }
        return $key;
    }
}