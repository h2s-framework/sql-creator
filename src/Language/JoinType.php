<?php

namespace Siarko\SqlCreator\Language;

use Siarko\SqlCreator\Language\Tokens\Token;

enum JoinType: string
{
    case LEFT = 'LEFT';
    case RIGHT = 'RIGHT';
    case INNER = 'INNER';

}