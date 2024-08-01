<?php

namespace Siarko\SqlCreator\DatabaseElement\AssocParser\Column;

use Siarko\SqlCreator\DatabaseElement\Column;
use Siarko\SqlCreator\DatabaseElement\ColumnFactory;
use Siarko\SqlCreator\DatabaseElement\DataTypeProvider;

class InformationSchemaParser implements ColumnParserInterface
{

    public function __construct(
        protected readonly ColumnFactory $columnFactory,
        protected readonly DataTypeProvider $dataTypeProvider
    )
    {
    }

    public function parse(array $data): Column
    {
        $column = $this->columnFactory->create(['name' => $data[self::COLUMN_COLUMN_NAME]]);
        $column->setTableName($data[self::COLUMN_TABLE_NAME]);
        $column->setType($this->dataTypeProvider->parseString($data[self::COLUMN_COLUMN_TYPE]));
        $column->setAutoIncrement(str_contains($data[self::COLUMN_EXTRA], 'auto_increment'));
        if($column->isAutoIncrement()){
            $column->setNullable(false);
            $column->setNoDefaultValue();
        }else{
            $column->setNullable(($data[self::COLUMN_IS_NULLABLE] == "YES"));
            if($data[self::COLUMN_DEFAULT_VALUE] != null && $data[self::COLUMN_DEFAULT_VALUE] != 'NULL'){
                $column->setDefaultValue($data[self::COLUMN_DEFAULT_VALUE]);
            }
        }

        return $column;
    }
}