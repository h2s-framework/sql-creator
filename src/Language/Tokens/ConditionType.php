<?php

namespace Siarko\SqlCreator\Language\Tokens;

enum ConditionType: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '<>';
    case LESS = '<';
    case GREATER = '>';
    case LESS_OR_EQUAL = '<=';
    case GREATER_OR_EQUAL = '>=';
    case LIKE = 'LIKE';
    case IS_NULL = 'IS NULL';
    case NOT_NULL = 'IS NOT NULL';
    case IN = 'IN';
    case BETWEEN = 'BETWEEN';
}