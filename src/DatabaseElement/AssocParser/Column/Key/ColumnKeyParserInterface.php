<?php

namespace Siarko\SqlCreator\DatabaseElement\AssocParser\Column\Key;

use Siarko\SqlCreator\DatabaseElement\Key\AbstractColumnKey;

interface ColumnKeyParserInterface
{
    public const KEY_TABLE_NAME = 'KEY_COLUMN_USAGE';

    public const COLUMN_KEY_TYPE = 'CONSTRAINT_NAME';
    public const COLUMN_KEY_FK_SCHEMA = 'TABLE_SCHEMA';
    public const COLUMN_KEY_FK_TABLE_SOURCE = 'TABLE_NAME';
    public const COLUMN_KEY_FK_TABLE_TARGET = 'REFERENCED_TABLE_NAME';
    public const COLUMN_KEY_FK_COLUMN_SOURCE = 'COLUMN_NAME';
    public const COLUMN_KEY_FK_COLUMN_TARGET = 'REFERENCED_COLUMN_NAME';

    public function parse(array $data): AbstractColumnKey;

}