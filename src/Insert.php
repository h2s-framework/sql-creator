<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 11.08.2018
 * Time: 18:05
 */

namespace Siarko\SqlCreator;

use JetBrains\PhpStorm\Pure;

class Insert extends \Siarko\SqlCreator\BasicQuery {

    protected array $insertedValues = [];
    protected array $usedColumns = [];

    /**
     * Insert constructor.
     * @param $data array
     * @param InsertMode $mode
     */
    function __construct(array $data, InsertMode $mode = InsertMode::ASSOC) {
        if($mode == InsertMode::MULTIPLE_ROWS){
            $maxL = 1;
            foreach ($data as &$value) {//znalezienie najwiekszej liczby wierszy + przeksztalcenie wartosci na tablice
                if(is_array($value)){
                    if(count($value) > $maxL){
                        $maxL = count($value);
                    }
                }else{
                    $value = [$value];
                }
            }

            foreach ($data as $k => $v) { //zapelnienie pustych wierszy
                $count = count($v);
                if($count < $maxL){
                    for($i = 1; $i <= $maxL-$count; $i++){
                        $data[$k][] = 'NULL';
                    }
                }
            }

            foreach ($data as $key => $values) {
                $this->useColumn($key);
                foreach ($values as $rowId => $v){
                    if(!array_key_exists($rowId, $this->insertedValues)){
                        $this->insertedValues[$rowId] = [];
                    }
                    $this->insertedValues[$rowId][] = $this->constructBoundValue($key, $v);
                }
            }
        }else{
            $valueSet = [];
            foreach ($data as $column => $value) {
                $this->useColumn($column);
                $valueSet[] = $this->constructBoundValue($column, $value);
            }
            $this->insertedValues[] = $valueSet;
        }
    }

    /**
     * @param $column
     */
    private function useColumn($column) {
        if (!in_array($column, $this->usedColumns)) {
            $this->usedColumns[] = $column;
        }
    }

    /**
     * @return string
     */
    #[Pure] private function constructInsertedValues(): string
    {
        $sql = '';
        $i = 0;
        foreach ($this->insertedValues as $insertedValue) {
            $sql .= '(';
            $sql .= implode(',',$insertedValue);
            $sql .= ')';
            if($i < count($this->insertedValues)-1){
                $sql .= ', ';
            }
            $i++;
        }
        return $sql;
    }

    /**
     * @param $table
     * @return $this
     */
    public function into($table): static
    {
        if (empty($this->tables)) {
            $this->addTable($table);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function parse(): string {
        $sql = "INSERT INTO";
        $sql .= ' '.$this->arrayToList($this->tables);
        $sql .= '(' . $this->arrayToList($this->usedColumns) . ') ';
        $sql .= 'VALUES ';
        $sql .= $this->constructInsertedValues();

        return $sql;
    }
}