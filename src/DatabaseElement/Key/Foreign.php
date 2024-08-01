<?php

namespace Siarko\SqlCreator\DatabaseElement\Key;

use Siarko\SqlCreator\DatabaseElement\KeyType;
use Siarko\Serialization\Api\Attribute\Serializable;

class Foreign extends AbstractColumnKey
{

    /**
     * @var string
     */
    #[Serializable]
    private string $sourceTable = '';
    /**
     * @var string
     */
    #[Serializable]
    private string $sourceColumn = '';
    /**
     * @var string
     */
    #[Serializable]
    private string $targetTable = '';
    /**
     * @var string
     */
    #[Serializable]
    private string $targetColumn = '';

    /**
     * @var string
     */
    #[Serializable]
    private string $constraintName = '';

    /**
     * @return string
     */
    public function getSourceTable(): string
    {
        return $this->sourceTable;
    }

    /**
     * @param string $sourceTable
     * @return Foreign
     */
    public function setSourceTable(string $sourceTable): static
    {
        $this->sourceTable = $sourceTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceColumn(): string
    {
        return $this->sourceColumn;
    }

    /**
     * @param string $sourceColumn
     * @return Foreign
     */
    public function setSourceColumn(string $sourceColumn): static
    {
        $this->sourceColumn = $sourceColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetTable(): string
    {
        return $this->targetTable;
    }

    /**
     * @param string $targetTable
     * @return Foreign
     */
    public function setTargetTable(string $targetTable): static
    {
        $this->targetTable = $targetTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetColumn(): string
    {
        return $this->targetColumn;
    }

    /**
     * @param string $targetColumn
     * @return Foreign
     */
    public function setTargetColumn(string $targetColumn): static
    {
        $this->targetColumn = $targetColumn;
        return $this;
    }

    /**
     * @param bool $generate
     * @return string
     */
    public function getKeyName(bool $generate = false): string
    {
        if(strlen($this->constraintName) == 0 && $generate){
            return $this->generateName();
        }
        return $this->constraintName;
    }

    /**
     * @param string $constraintName
     * @return Foreign
     */
    public function setConstraintName(string $constraintName): static
    {
        $this->constraintName = $constraintName;
        return $this;
    }

    /**
     * @param Foreign $foreign
     * @return bool
     */
    public function equals(Foreign $foreign): bool
    {
        return ($this->getSourceTable() === $foreign->getSourceTable()) &&
            ($this->getSourceColumn() === $foreign->getSourceColumn()) &&
            ($this->getTargetTable() === $foreign->getTargetTable()) &&
            ($this->getTargetColumn() === $foreign->getTargetColumn());
    }


    /**
     * @param AbstractColumnKey $key
     * @return bool
     */
    public function matches(AbstractColumnKey $key): bool
    {
        if($key instanceof Foreign){
            return $this->equals($key);
        }
        return false;
    }

    /**
     * @return string
     */
    protected function generateName(): string
    {
        return 'FK_'.$this->getSourceTable().'_'
            .$this->getSourceColumn().'_'
            .$this->getTargetTable().'_'
            .$this->getTargetColumn();
    }


}