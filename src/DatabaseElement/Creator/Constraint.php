<?php

namespace Siarko\SqlCreator\DatabaseElement\Creator;

use Siarko\SqlCreator\BasicQuery;
use Siarko\SqlCreator\DatabaseElement\Key\AbstractColumnKey;
use Siarko\SqlCreator\DatabaseElement\Key\Foreign;
use Siarko\SqlCreator\DatabaseElement\Key\Primary;
use Siarko\SqlCreator\Language\Tokens\Token;

class Constraint
{
    public function createSql(AbstractColumnKey $key): string
    {
        if($key instanceof Primary){
            return BasicQuery::tokenSequence([Token::PRIMARY,Token::KEY]).'('.$key->getColumnName().')';
        }
        if($key instanceof Foreign){
            $sql = $key->getKeyName(true).Token::SPACE->value;
            $sql .= BasicQuery::tokenSequence([Token::FOREIGN,Token::KEY]).'('.$key->getSourceColumn().')';
            $sql .= BasicQuery::tokenSequence([Token::SPACE,Token::REFERENCES]);
            return $sql.$key->getTargetTable().'('.$key->getTargetColumn().')';
        }
        return '';
    }

    public function dropSql(AbstractColumnKey $key): string
    {
        if($key instanceof Primary){
            return BasicQuery::tokenSequence([Token::DROP,Token::PRIMARY,Token::KEY]);
        }
        if($key instanceof Foreign){
            return BasicQuery::tokenSequence([Token::DROP,Token::FOREIGN,Token::KEY]).$key->getKeyName();
        }
        return '';
    }

}