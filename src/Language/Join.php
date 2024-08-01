<?php

namespace Siarko\SqlCreator\Language;

use Siarko\SqlCreator\DatabaseElement\Column;
use Siarko\SqlCreator\DatabaseElement\Table;
use Siarko\SqlCreator\Language\Tokens\Token;

class Join
{

    protected JoinType $type = JoinType::LEFT;


    public function __construct(
        protected Table $source,
        protected Table $target,
        protected string $sourceColumnName = '',
        protected string $targetColumnName = ''
    )
    {
    }

    /**
     * @return Column
     */
    public function getSourceColumn(): Column
    {
        if(strlen($this->sourceColumnName) > 0){
            return $this->source->getColumn($this->sourceColumnName);
        }
        return current($this->source->getColumns());
    }

    /**
     * @return Column
     */
    public function getTargetColumn(): Column
    {
        if(strlen($this->targetColumnName) > 0){
            return $this->target->getColumn($this->targetColumnName);
        }
        return current($this->target->getColumns());
    }

    public function __toString(): string
    {
        $columnSource = $this->getSourceColumn();
        $columnTarget = $this->getTargetColumn();
        $result = $this->type->value.Token::SPACE->value.Token::JOIN->value.Token::SPACE->value;
        $result .= $this->target->getName().Token::SPACE->value;
        if($this->target->hasAlias()){
            $result .= Token::AS->value.Token::SPACE->value.$this->target->getAlias().Token::SPACE->value;
        }
        $result .= Token::ON->value.Token::SPACE->value.$this->source->getAlias().'.'.$columnSource->getName();
        $result .= '='.$this->target->getAlias().'.'.$columnTarget->getName();
        return $result;
    }


}