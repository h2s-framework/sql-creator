<?php

namespace Siarko\SqlCreator;

enum InsertMode
{
    /*
     * $data = [
     *      ['column1', 'column2'],
     *      [
     *          ['value1.1', 'value1.2'],
     *          ['value2.1', 'value2.2']
     *      ]
     * ]
     * */
    case MULTIPLE_ROWS;
    /*
     * $data = [
     *      'col1' => value1,
     *      'col2' => value2,
     *      'col3' => value3
     *      ...
     * ]
     * */
    case ASSOC;
}