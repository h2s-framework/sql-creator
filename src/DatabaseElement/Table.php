<?php

namespace Siarko\SqlCreator\DatabaseElement;

use JetBrains\PhpStorm\Pure;
use Siarko\Serialization\Api\Attribute\Serializable;

class Table
{

    #[Serializable]
    protected string $alias = '';

    /**
     * @var Column[]
     */
    #[Serializable]
    protected array $columns = [];


    public function __construct(
        #[Serializable] protected string $name
    )
    {
        $matches = [];
        preg_match("#(?<name>[a-zA-Z0-9-_]+)(\s+as\s+(?<alias>[a-zA-Z0-9-_]+))?#i", $this->name, $matches);
        if(array_key_exists('alias', $matches)){
            $this->setAlias($matches['alias']);
            $this->name = $matches['name'];
        }
    }

    /**
     * Returns name if alias is empty
     * @return string
     */
    #[Pure] public function getAlias(): string
    {
        if(strlen($this->alias)){
            return $this->alias;
        }
        return $this->getName();
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function hasAlias(): bool
    {
        return strlen($this->alias) > 0;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function addColumn(Column $column): static
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    public function setColumns(array $columns): static
    {
        $this->columns = $columns;
        return $this;
    }

    public function hasColumn(string $name): bool
    {
        return array_key_exists($name, $this->columns);
    }

    #[Pure] public function getColumn(string $name): ?Column
    {
        if($this->hasColumn($name)){
            return $this->columns[$name];
        }
        return null;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}