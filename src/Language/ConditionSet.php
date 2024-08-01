<?php

namespace Siarko\SqlCreator\Language;

use Siarko\SqlCreator\Language\Tokens\ConditionLink;
use Siarko\SqlCreator\Language\Condition;
use Siarko\SqlCreator\Language\Tokens\ConditionType;
use Siarko\SqlCreator\Language\Tokens\Token;

class ConditionSet
{

    /**
     * @var Condition|ConditionSet[]
     */
    private array $conditions = [];

    /**
     * @var ConditionLink[]
     */
    private array $conditionLinks = [];

    /**
     * @throws \Siarko\SqlCreator\Exceptions\IncorrectConditionData
     * @throws \Siarko\SqlCreator\Exceptions\IncorrectConditionType
     */
    public function extend(array $data){
        $conditionLink = ConditionLink::AND;
        foreach ($data as $key => $value) {
            if(is_array($value)){
                if(static::isConditionDescription($value)){
                    $condition = new Condition($value);
                }else{
                    $condition = new ConditionSet();
                    $condition->extend($value);
                }
                $this->addCondition($condition, $conditionLink);
                if($conditionLink != ConditionLink::AND){
                    $conditionLink = ConditionLink::AND;
                }
            }
            if($value instanceof ConditionLink){
                $conditionLink = $value;
            }
        }
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return count($this->conditions) == 0;
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function isConditionDescription(array $data): bool
    {
        return count($data) == 1 || (count($data) == 2 && end($data) instanceof ConditionType);
    }

    public function addCondition(Condition|ConditionSet $condition, ConditionLink $linkType = ConditionLink::AND){
        if(count($this->conditions) > 0){
            $lastKey = array_key_last($this->conditions);
            $this->addLink($lastKey, $lastKey+1, $linkType);
        }
        $this->conditions[] = $condition;
    }

    /**
     * @param int $key1
     * @param int $key2
     * @param ConditionLink $link
     */
    protected function addLink(int $key1, int $key2, ConditionLink $link = ConditionLink::OR){
        $this->conditionLinks[$this->getIndex($key1, $key2)] = $link;
    }

    /**
     * @param int $key1
     * @param int $key2
     * @return ConditionLink
     */
    protected function getLinkType(int $key1, int $key2): ConditionLink
    {
        $index1 = $this->getIndex($key1, $key2);
        $index2 = $this->getIndex($key2, $key1);
        if(array_key_exists($index1, $this->conditionLinks)){
            return $this->conditionLinks[$index1];
        }elseif (array_key_exists($index2, $this->conditionLinks)){
            return $this->conditionLinks[$index2];
        }
        return ConditionLink::AND;
    }

    /**
     * @param int $key1
     * @param int $key2
     * @return string
     */
    protected function getIndex(int $key1, int $key2): string
    {
        return $key1.'.'.$key2;
    }

    public function __toString(): string
    {
        $result = '';
        foreach ($this->conditions as $key => $condition) {
            $result .= '('.$condition->__toString().')';
            if($key < count($this->conditions)-1){
                $result .= Token::SPACE->value.$this->getLinkType($key, $key+1)->name.Token::SPACE->value;
            }
        }
        return $result;
    }


}