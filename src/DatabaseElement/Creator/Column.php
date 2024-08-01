<?php

namespace Siarko\SqlCreator\DatabaseElement\Creator;

use Siarko\SqlCreator\BasicQuery;
use Siarko\SqlCreator\Language\Tokens\Token;

class Column
{
    /**
     * @param \Siarko\SqlCreator\DatabaseElement\Column $column
     * @return string
     */
    public function createSql(\Siarko\SqlCreator\DatabaseElement\Column $column): string
    {
        $sql = $column->getName().Token::SPACE->value.$column->getType();
        if($column->isAutoIncrement()){
            $sql .= BasicQuery::tokenSequence([Token::SPACE, Token::NOT_NULL, Token::SPACE, Token::AUTO_INCREMENT]);
        }else{
            if($column->isNullable()){
                $sql .= BasicQuery::tokenSequence([Token::SPACE, Token::NULL]);
            }else{
                $sql .= BasicQuery::tokenSequence([Token::SPACE, Token::NOT_NULL]);
            }
            if($column->hasDefaultValue()){
                $defaultValue = $this->prepareDefaultValue($column->getDefaultValue());
                if($column->getDefaultValue() !== null){
                    $sql .= Token::SPACE->value.Token::DEFAULT->value.'('.$defaultValue.')';
                }else{
                    $sql .= Token::SPACE->value.Token::DEFAULT->value.' '.$defaultValue;
                }
            }
        }
        return $sql;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function prepareDefaultValue(mixed $value): string
    {
        if($value === null){
            return Token::NULL->value;
        }
        if(is_numeric($value)){
            return $value;
        }
        if(is_string($value)){
            if(str_ends_with($value, '()')){
                return $value;
            }else{
                return "'".$value."'";
            }
        }
        if(is_bool($value)){
            return ($value ? Token::TRUE->value : Token::FALSE->value);
        }
        return (string)$value;
    }

}