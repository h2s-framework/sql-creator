<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 30.04.2019
 * Time: 16:02
 */
namespace Siarko\SqlCreator\Language;

use JetBrains\PhpStorm\Pure;
use Siarko\SqlCreator\Language\Tokens\ConditionType;
use Siarko\SqlCreator\Language\Tokens\Token;
use Siarko\SqlCreator\Exceptions\IncorrectConditionData;
use Siarko\SqlCreator\Exceptions\IncorrectConditionType;

class Condition{

    private ConditionType $sign = ConditionType::EQUAL;
    private string $field;
    private $value = null;

    /**
     * @var callable[]
     */
    private array $conditionTypeInterpreters = [];

    /**
     * ['column' => 'value']
     * OR
     * ['column' => 'value', ConditionType]
     * @param array $data
     * @throws IncorrectConditionType
     * @throws IncorrectConditionData
     */
    public function __construct(array $data = []){
        $count = count($data);
        if($count > 2){
            throw new IncorrectConditionData($data);
        }
        if($count > 0){
            $this->setValue(reset($data));
            $this->setField(key($data));
            if($count == 2){ //type defined
                $type = next($data);
                if($type instanceof ConditionType){
                    $this->setSign($type);
                }else{
                    throw new IncorrectConditionType($type);
                }
            }
        }
        $this->conditionTypeInterpreters[ConditionType::NOT_NULL->name] = function($field, $value){
            return $this->getColumnString().Token::SPACE->value.Token::IS_NOT_NULL->value;
        };
        $this->conditionTypeInterpreters[ConditionType::BETWEEN->name] = function($field, $value){
            return $this->getColumnString().Token::SPACE->value.
                Token::BETWEEN->value.Token::SPACE->value.
                $this->getValueString($value[0]).Token::SPACE->value.Token::AND->value.
                Token::SPACE->value.$this->getValueString($value[1]);
        };
    }

    public function setField($field): static
    {
        $this->field = $field;
        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return ConditionType
     */
    public function getSign(): ConditionType
    {
        return $this->sign;
    }

    /**
     * @param ConditionType $sign
     * @return Condition
     */
    public function setSign(ConditionType $sign): static
    {
        $this->sign = $sign;
        return $this;
    }

    /**
     * @return null
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param null $value
     */
    public function setValue($value): static
    {
        if(is_string($value) && str_starts_with($value, ":")){
            $this->value = new BoundParam($value);
        }elseif(is_array($value)){
            $this->value = array_map(function($value){
                return (is_string($value) && str_starts_with($value, ":") ? new BoundParam($value) : $value);
            }, $value);
        }else{
            $this->value = $value;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function parse(): string
    {
        if($this->value == null and $this->field == null){
            return '';
        }
        return $this->runConditionInterpreter();
    }

    protected function getColumnString(): string
    {
        return $this->quote($this->field);
    }

    /**
     * @return string
     */
    #[Pure] protected function getValueString($value = null): string
    {
        if($value == null){
            $value = $this->value;
        }
        if($value === null){
            return Token::NULL->value;
        }
        if(is_array($value)){
            return '(\''.implode('\',\'', $value).'\')';
        }
        if(is_string($value)){
            return $this->quote($value,'\'');
        }
        if(is_object($value)){
            return $value->__toString();
        }
        return $value;
    }

    protected function quote(string $s, string $char = '`'): string
    {
        return $char.$s.$char;
    }

    protected function runConditionInterpreter(): string
    {
        if(array_key_exists($this->sign->name, $this->conditionTypeInterpreters)){
            return $this->conditionTypeInterpreters[$this->sign->name]($this->field, $this->value);
        }
        $result = $this->getColumnString();
        $result .= Token::SPACE->value.$this->sign->value;
        $result .= Token::SPACE->value.$this->getValueString();
        return $result;
    }

    #[Pure] public function __toString(): string
    {
        return $this->parse();
    }

}