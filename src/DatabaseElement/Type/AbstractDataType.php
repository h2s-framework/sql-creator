<?php

namespace Siarko\SqlCreator\DatabaseElement\Type;

use Siarko\Serialization\Api\Attribute\Serializable;

abstract class AbstractDataType
{
    /**
     * @param string $name
     * @param string|null $length
     */
    protected function __construct(
        #[Serializable] protected string $name,
        #[Serializable] protected ?string $length = null
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getLength(): ?string
    {
        return $this->length;
    }

    /**
     * @param string|null $length
     * @return AbstractDataType
     */
    public function setLength(?string $length): static
    {
        $this->length = $length;
        return $this;
    }

    public function __toString() {
        return $this->getName().(($this->getLength() !== null)?'('.$this->getLength().')':'');
    }

    public abstract function cast(mixed $value): mixed;

    public function equals(AbstractDataType $dataType): bool
    {
        return strtolower($this->getName()) === strtolower($dataType->getName()) && $this->length === $dataType->length;
    }

    public abstract function getPhpType(): string;

}