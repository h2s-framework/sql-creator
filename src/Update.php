<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 11.08.2018
 * Time: 23:41
 */

namespace Siarko\SqlCreator;

use Siarko\SqlCreator\Exceptions\MultipleUpdateColumns;
use Siarko\SqlCreator\Language\Tokens\Token;

class Update extends BasicQuery {

    protected array $changes = [];

    function __construct(string|array $tables) {
        $this->parseArgument($tables, function($tableName){
            $this->addTable($tableName);
        });
        return $this;
    }

    /**
     * $columns = ['kolumna1', 'kolumna2']
     * $values = ['wartosc1', 'wartosc2']
     *
     * $columns = [ 'kolumna1' => 'wartosc', 'kolumna2' => 'wartosc2']
     * @param $columns array
     * @param array $values
     * @return SelectiveQuery
     * @throws MultipleUpdateColumns
     */
    public function set(array $columns, array $values = []): SelectiveQuery
    {
        $changes = [];
        if(!empty($values)){
            foreach ($columns as $key => $column) {
                if(array_key_exists($column, $this->changes)){
                    throw new MultipleUpdateColumns();
                }
                $changes[$column] = $values[$key];
            }
        }else{
            $changes = $columns;
        }
        foreach ($changes as $column => $value) {
            $this->changes[$column] = $this->constructBoundValue($column, $value);
        }
        return new SelectiveQuery($this);
    }

    public function parse(): string
    {
        $sql = 'UPDATE ';
        $sql .= $this->arrayToList($this->tables);
        if(count($this->changes) > 0){
            $sql .= 'SET ';
            $sql .= $this->createSetString();
        }
        return $sql;
    }

    private function createSetString(): string
    {
        $sets = [];
        foreach ($this->changes as $column => $value) {
            $sets[] = $column.'='.$this->quoteValue($value);
        }
        return implode(',', $sets).Token::SPACE->value;
    }
}