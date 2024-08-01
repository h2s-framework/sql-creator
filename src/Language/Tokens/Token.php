<?php

namespace Siarko\SqlCreator\Language\Tokens;

enum Token: string
{
    case SPACE = ' ';
    case SELECT = 'SELECT';
    case WHERE = 'WHERE';
    case NULL = 'NULL';
    case TRUE = 'TRUE';
    case FALSE = 'FALSE';
    case NOT_NULL = 'NOT NULL';
    case IS_NOT_NULL = 'IS NOT NULL';
    case BETWEEN = 'BETWEEN';
    case AND = 'AND';
    case JOIN = 'JOIN';
    case LEFT = 'LEFT';
    case ON = 'ON';
    case AS = 'AS';
    case DEFAULT = 'DEFAULT';
    case AUTO_INCREMENT = 'AUTO_INCREMENT';

    case CONSTRAINT = 'CONSTRAINT';
    case PRIMARY = 'PRIMARY';
    case FOREIGN = 'FOREIGN';
    case REFERENCES = 'REFERENCES';
    case KEY = 'KEY';

    case CREATE = 'CREATE';
    case DROP = 'DROP';
    case ALTER = 'ALTER';
    case TABLE = 'TABLE';

    case ADD = 'ADD';
    case COLUMN = 'COLUMN';
    case MODIFY = 'MODIFY';

    case WITHOUT = 'WITHOUT';
    case VALIDATION = 'VALIDATION';


}