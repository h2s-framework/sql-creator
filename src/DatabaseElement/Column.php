<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 16.04.2019
 * Time: 23:10
 */

namespace Siarko\SqlCreator\DatabaseElement;


use Siarko\SqlCreator\DatabaseElement\Key\AbstractColumnKey;
use Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType;
use Siarko\Serialization\Api\Attribute\Serializable;

class Column
{

    #[Serializable]
    private AbstractDataType $type;
    /**
     * @var AbstractColumnKey[]
     */
    #[Serializable]
    private array $keys = [];
    #[Serializable]
    private bool $nullable = true;
    #[Serializable]
    private bool $autoIncrement = false;
    #[Serializable]
    private mixed $defaultValue = null;
    #[Serializable]
    private bool $hasDefaultValue = false;
    #[Serializable]
    private string $tableName = '';

    /**
     * @param string $name
     */
    function __construct(
        #[Serializable] private string $name
    ){
    }

    /**
     * @param AbstractDataType $type
     */
    public function setType(AbstractDataType $type){
        $this->type = $type;
    }

    /**
     * @param AbstractColumnKey $key
     */
    public function addKey(AbstractColumnKey $key){
        $this->keys[$key->getKeyName()] = $key;
    }

    public function setKeys(array $keys): static
    {
        $this->keys = $keys;
        return $this;
    }

    /**
     * @param string $name
     */
    public function removeKey(string $name){
        if(array_key_exists($name, $this->keys)){
            unset($this->keys[$name]);
        }
    }

    /**
     * @param bool $flag
     */
    public function setNullable(bool $flag){
        $this->nullable = $flag;
    }

    /**
     * @param bool $flag
     */
    public function setAutoIncrement(bool $flag){
        $this->autoIncrement = $flag;
    }

    /**
     * @param mixed $value
     * @param bool $cast
     */
    public function setDefaultValue(mixed $value, bool $cast = true){
        if($cast){
            $value = $this->getType()->cast($value);
        }
        $this->defaultValue = $value;
        $this->hasDefaultValue = true;
    }

    public function setNoDefaultValue(){
        $this->hasDefaultValue = false;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    /**
     * @return AbstractDataType
     */
    public function getType(): AbstractDataType
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @param KeyType $keyType
     * @return AbstractColumnKey|null
     */
    public function getKey(KeyType $keyType): ?AbstractColumnKey
    {
        foreach ($this->keys as $key) {
            if($keyType->isKey($key)){
                return $key;
            }
        }
        return null;
    }

    public function isKey(KeyType $key): bool
    {
        return (array_key_exists($key->name, $this->keys));
    }

    public function isPrimaryKey(): bool
    {
        return $this->isKey(KeyType::PRIMARY);
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    /**
     * @return mixed|null
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return Column
     */
    public function setTableName(string $tableName): static
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * @param Column $column
     * @return bool
     */
    public function keysMatch(Column $column): bool
    {
        if(count($this->keys) != count($column->keys)){
            return false;
        }
        foreach ($this->keys as $keyName => $key) {
            if(!array_key_exists($keyName, $column->keys) || !$key->matches($column->keys[$keyName])){
                return false;
            }
        }
        return true;
    }

    /**
     * @param Column $columnData
     * @param bool $matchKeys
     * @return bool
     */
    public function matches(Column $columnData, bool $matchKeys = true): bool
    {
        if($matchKeys && !$this->keysMatch($columnData)){
            return false;
        }
        if(
            $this->name !== $columnData->name ||
            $this->tableName !== $columnData->tableName ||
            $this->nullable !== $columnData->nullable ||
            $this->autoIncrement !== $columnData->autoIncrement ||
            !$this->type->equals($columnData->type) ||
            $this->hasDefaultValue !== $columnData->hasDefaultValue ||
            $this->defaultValue !== $columnData->defaultValue
        ){
            return false;
        }
        return true;
    }

}