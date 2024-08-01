<?php

namespace Siarko\SqlCreator\DatabaseElement\Type\Text;

class Char extends \Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType
{

    public function __construct(string $name = 'char', ?string $length = "1")
    {
        parent::__construct($name, $length);
    }

    public function cast(mixed $value): mixed
    {
        if(is_string($value)){
            $result = [];
            preg_match("#^'(?<text>.*)'$#", $value, $result);
            if(array_key_exists('text', $result)){
                $value = $result['text'];
            }
            return $value;
        }else{
            return (string)$value;
        }
    }

    public function getPhpType(): string
    {
        return 'string';
    }
}