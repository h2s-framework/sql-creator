<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 11.08.2018
 * Time: 23:18
 */

namespace Siarko\SqlCreator;


use Siarko\SqlCreator\Language\Tokens\Token;

class Delete extends BasicQuery {

    /**
     * @param $tableNames string|string[]
     */
    public function from(array|string $tableNames){
        $this->parseArgument($tableNames, function($tableName){
            $this->addTable($tableName);
        });
        return new SelectiveQuery($this);
    }

    /**
     * @return string
     */
    public function parse(): string
    {
        return $this->tokenSequence([
            Token::DROP,Token::TABLE
            ]).$this->arrayToList($this->tables);
    }
}