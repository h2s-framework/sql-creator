<?php


/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 10.08.2018
 * Time: 17:35
 */

namespace Siarko\SqlCreator;

use Siarko\SqlCreator\DatabaseElement\Column;
use Siarko\SqlCreator\DatabaseElement\Table;
use Siarko\SqlCreator\Language\Join;
use Siarko\SqlCreator\Language\Tokens\Token;

class Select extends BasicQuery
{

    /**
     * @var string[]
     */
    public array $selectedColumns = [];

    /**
     * @var Join[]
     */
    protected array $joins = [];

    /**
     * @param string $columnName
     */
    private function addColumn(string $columnName, string $alias = '')
    {
        if (!in_array($columnName, $this->selectedColumns)) {
            $this->selectedColumns[] = $columnName.(strlen($alias) > 0 ?' as \''.$alias.'\'' : '');
        }
    }

    /**
     * Select constructor.
     * @param string[] $columns
     */
    function __construct(string|array $columns = [])
    {
        if (is_string($columns)) {
            $columns = [$columns];
        }
        $this->parseArgument($columns, function ($column, $key) {
            $alias = '';
            if(!is_numeric($key)){
                $alias = $column;
                $column = $key;
            }
            if (is_string($column)) {
                $this->addColumn($column, $alias);
            }
        });
    }

    /**
     * @param string|array $tableNames
     * @return SelectiveQuery
     */
    public function from(string|array $tableNames): SelectiveQuery
    {
        $this->parseArgument($tableNames, function ($tableName) {
            $this->addTable($tableName);
        });

        return new SelectiveQuery($this);
    }

    public function join(string $tableSource, string $fieldSource, string $tableTarget, string $fieldTarget = ''):static
    {
        if(strlen($fieldTarget) == 0){$fieldTarget = $fieldSource;}
        $this->joins[] = new Join(
            (new Table($tableSource))->addColumn(new Column($fieldSource)),
            (new Table($tableTarget))->addColumn(new Column($fieldTarget))
        );
        return $this;
    }

    protected function getJoinString(array $joinData): string
    {
        return implode(Token::SPACE->value, $joinData).((count($this->joins) > 0)?Token::SPACE->value:'');
    }


    /**
     * @return string
     */
    public function parse(): string
    {
        $result = 'SELECT ';
        $result .= $this->arrayToList($this->selectedColumns);
        $result .= 'FROM '.$this->arrayToList($this->tables);
        $result .= $this->getJoinString($this->joins);
        return $result;
    }
}