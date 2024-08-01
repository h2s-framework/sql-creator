<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 10.08.2018
 * Time: 17:34
 */

namespace Siarko\SqlCreator;


use Siarko\SqlCreator\Language\Tokens\Token;

abstract class BasicQuery {

    protected int $bindIndex = 0;

    /**
     * @var string[]
     */
    protected array $tables = [];

    /**
     * @var array
     */
    protected array $boundParams = [];

    /**
     * @var bool
     */
    protected bool $autoBindValues = true;

    /**
     * @param $tableName string
     */
    protected function addTable(string $tableName){
        if(!in_array($tableName, $this->tables)){
            $this->tables[] = $tableName;
        }
    }

    /**
     * @param $argument array|string
     * @param $handler callable
     */
    protected function parseArgument(array|string $argument, callable $handler){
        if(is_callable($handler)){
           if(is_array($argument)){
               foreach ($argument as $key => $item) {
                   $handler($item, $key);
               }
           }else{
               $handler($argument);
           }
        }
    }

    /**
     * @param array $array array|string
     * @param string $separator
     * @param bool $addSpace
     * @param bool $onKeys
     * @param string $enclose
     * @return string
     */
    protected function arrayToList(array $array, string $separator = ', ', bool $addSpace = true, bool $onKeys = false, string $enclose = ''): string
    {
        $string = '';
        $index = 0;
        foreach ($array as $key => $item) {
            $v = (($onKeys)?$key:$item);
            if($v !== null){
                if(gettype($v) === 'string'){
                    $string .= $enclose.$v.$enclose;
                }else{
                    $string .= $v;
                }
            }else{
                $string .= 'NULL';
            }
            if($index < count($array)-1){
                $string .= $separator;
            }
            $index++;
        }
        return $string.(($addSpace)?' ':'');
    }

    /**
     * Bind query params
     * @param array|string $name
     * @param null $value
     * @return $this
     */
    public function bind(array|string $name, $value = null): static
    {
        if(is_string($name)){
            $this->boundParams[$name] = $value;
        }else{
            foreach ($name as $paramName => $paramValue) {
                $this->boundParams[$paramName] = $paramValue;
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getBinds(): array
    {
        return $this->boundParams;
    }

    /**
     * @param Token[] $tokens
     * @param Token|string $separator
     * @return string
     */
    public static function tokenSequence(array $tokens, Token|string $separator = Token::SPACE, bool $addSpace = true): string
    {
        $separator = (($separator instanceof Token) ? $separator->value : $separator);
        $tokenValues = array_map(function(Token $token){ return $token->value;}, $tokens);
        return implode($separator, $tokenValues).($addSpace?$separator:'');
    }

    /**
     * Quote value if it's not bindName
     * @param $value
     * @return mixed
     */
    protected function quoteValue($value)
    {
        if($value === null){
            return 'NULL';
        }
        if(is_string($value) && str_starts_with($value, ":")){
            return $value;
        }
        return '\''.$value.'\'';
    }

    /**
     * @param string $column
     * @param $value
     * @return mixed
     */
    protected function constructBoundValue(string $column, $value): mixed
    {
        if($this->isAutoBindValues()){
            if(is_array($value)){
                return array_map(function($v) use ($column){
                    return $this->constructBoundValue($column, $v);
                }, $value);
            }else{
                if($this->isBindName($value)){ return $value;}
                $bindName = $this->getBindName($column);
                $this->bind($bindName, $value);
                return $bindName;
            }
        }
        return $value;
    }

    protected function isBindName($value){
        return str_starts_with($value, ":");
    }

    protected function getBindName(string $column): string
    {
        return ':'.$column.'_'.$this->bindIndex++;
    }

    /**
     * @return bool
     */
    public function isAutoBindValues(): bool
    {
        return $this->autoBindValues;
    }

    /**
     * @param bool $autoBindValues
     * @return ConditionedQuery
     */
    public function setAutoBindValues(bool $autoBindValues): static
    {
        $this->autoBindValues = $autoBindValues;
        return $this;
    }

    /**
     * @return string
     */
    public abstract function parse(): string;
}