<?php

namespace Siarko\SqlCreator\DatabaseElement\AssocParser\Column;

use Siarko\SqlCreator\DatabaseElement\Column;

interface ColumnParserInterface
{
    public const TABLE_STRUCTURE_INFO = 'COLUMNS';

    public const COLUMN_TABLE_NAME = 'TABLE_NAME';
    public const COLUMN_TABLE_SCHEMA = 'TABLE_SCHEMA';
    public const COLUMN_COLUMN_NAME = 'COLUMN_NAME';
    public const COLUMN_DEFAULT_VALUE = 'COLUMN_DEFAULT';
    public const COLUMN_IS_NULLABLE = 'IS_NULLABLE';
    public const COLUMN_COLUMN_TYPE = 'COLUMN_TYPE';
    public const COLUMN_EXTRA = 'EXTRA';

    public function parse(array $data): Column;

}