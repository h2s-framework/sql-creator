<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 16.04.2019
 * Time: 23:07
 */

namespace Siarko\SqlCreator;

use Siarko\SqlCreator\DatabaseElement\Creator\Column;
use Siarko\SqlCreator\DatabaseElement\Creator\Constraint;
use Siarko\SqlCreator\DatabaseElement\Table;
use Siarko\SqlCreator\Language\Tokens\Token;

class CreateTable extends BasicQuery {

    function __construct(
        protected Table $table,
    ) {
    }

    private function getColumns(): string
    {
        $columnCreator = new Column();
        $columnsStrings = [];
        foreach ($this->table->getColumns() as $column) {
            $columnsStrings[] = $columnCreator->createSql($column);
        }
        return implode(','.Token::SPACE->value, $columnsStrings);
    }

    protected function getKeySql(): string
    {
        $keySqlCreator = new Constraint();
        $constraints = [];
        foreach ($this->table->getColumns() as $column) {
            foreach ($column->getKeys() as $key) {
                $constraints[] = BasicQuery::tokenSequence([Token::CONSTRAINT]).$keySqlCreator->createSql($key);
            }
        }
        return implode(',', $constraints);
    }

    public function parse(): string
    {
        $keys = $this->getKeySql();
        $keysSql = (strlen($keys) > 0 ? ','.Token::SPACE->value.$keys:'');
        return $this->tokenSequence([Token::CREATE, Token::TABLE, Token::SPACE])
            .$this->table->getName().'('.$this->getColumns().Token::SPACE->value.$keysSql.')';
    }

    function __toString() {
        return $this->parse();
    }
}