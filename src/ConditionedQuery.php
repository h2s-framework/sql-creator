<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 11.08.2018
 * Time: 01:38
 */

namespace Siarko\SqlCreator;

use JetBrains\PhpStorm\Pure;
use Siarko\SqlCreator\Language\ConditionSet;
use Siarko\SqlCreator\Language\Tokens\ConditionType;
use Siarko\SqlCreator\Language\Tokens\Token;
use Siarko\SqlCreator\Language\Condition;
use Siarko\SqlCreator\Traits\LimitingQuery;
use Siarko\SqlCreator\Traits\OffsetingQuery;

abstract class ConditionedQuery extends \Siarko\SqlCreator\BasicQuery {

    protected ConditionSet $conditionSet;

    use LimitingQuery, OffsetingQuery;

    public function __construct()
    {
        $this->conditionSet = new ConditionSet();
    }

    /**
     * @param Condition $condition
     */
    protected function addCondition(Condition $condition){
        $this->conditionSet->addCondition($condition);
    }

    /**
     * [
     *   'kolumna' => 'wartosc',
     *   Condition::AND_,
     *   ['kolumna2' => 'wartosc2', Condition::EQUAL],
     *   ['kolumna3', Condition::NOT_NULL]
     * ]
     * @param array|Condition $conditions
     * @return $this
     * @throws Exceptions\IncorrectConditionData
     * @throws Exceptions\IncorrectConditionType
     */
    public function where(array|Condition $conditions): static
    {
        if(gettype($conditions) == 'array'){
            $count = count($conditions);
            if($count > 0){//w formie tablicy asocjacyjnej
                if(
                    $count == 1 ||
                    $count == 2 && $conditions[array_key_last($conditions)] instanceof ConditionType
                ){
                    $conditions = [$conditions]; //package condition
                }
                if($this->isAutoBindValues()){
                    $this->autoBindConditionValues($conditions); //binding values
                }
                $this->extendConditionSet($conditions);
            }
        }

        if(gettype($conditions) == 'object' and $conditions instanceof Condition){
            //w formie obiektu klasy Condition
            $this->addCondition($conditions);
        }

        return $this;
    }

    protected function autoBindConditionValues(array &$conditions)
    {
        foreach ($conditions as $index => $condition) {
            if(is_array($condition)){
                if(ConditionSet::isConditionDescription($condition)){
                    if(count($condition) > 2){continue;} //incorrect condition, let's skip and handle later
                    $value = current($condition);
                    $columnName = key($condition);
                    $conditions[$index][$columnName] = $this->constructBoundValue($columnName, $value);
                }else{
                    $this->autoBindConditionValues($conditions);
                }
            }
        }
    }

    /**
     * @param array $conditionDescriptions
     * @throws Exceptions\IncorrectConditionData
     * @throws Exceptions\IncorrectConditionType
     */
    protected function extendConditionSet(array $conditionDescriptions){
        $this->conditionSet->extend($conditionDescriptions);
    }

    /**
     * @return string
     */
    protected function createConditionString(): string
    {
        if(!$this->conditionSet->isEmpty()){
            return Token::WHERE->value.Token::SPACE->value.$this->conditionSet->__toString();
        }
        return '';
    }

}