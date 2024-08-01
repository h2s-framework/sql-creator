<?php

namespace Siarko\SqlCreator;

use Siarko\SqlCreator\Language\Tokens\Token;

class Drop extends BasicQuery
{
    public function table(string $name): static
    {
        $this->addTable($name);
        return $this;
    }

    public function parse(): string
    {
        return BasicQuery::tokenSequence([Token::DROP,Token::TABLE])
            .implode(','.Token::SPACE->value, $this->tables);
    }
}