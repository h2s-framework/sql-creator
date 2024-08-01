<?php

namespace Siarko\SqlCreator\DatabaseElement\Key;

use JetBrains\PhpStorm\Pure;
use Siarko\SqlCreator\DatabaseElement\KeyType;

class Primary extends AbstractColumnKey
{
    public function getKeyName(): string
    {
        return KeyType::PRIMARY->name;
    }

    /**
     * @param AbstractColumnKey $key
     * @return bool
     */
    #[Pure] public function matches(AbstractColumnKey $key): bool
    {
        if($key instanceof Primary){
            return $key->getKeyName() === $this->getKeyName();
        }
        return false;
    }
}