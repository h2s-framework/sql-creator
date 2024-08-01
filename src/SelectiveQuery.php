<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 10.08.2018
 * Time: 17:34
 */

namespace Siarko\SqlCreator;

use ReflectionClass;

class SelectiveQuery extends ConditionedQuery {

    protected array $selectedColumns = [];
    protected BasicQuery $nativeObject;

    function __construct(BasicQuery $object) {
        parent::__construct();
        $this->nativeObject = $object;
    }

    /**
     * @return string
     */
    function __toString() {
        $string = $this->nativeObject->parse();
        $string .= $this->createConditionString();
        $string .= $this->getLimitSql().$this->getOffsetSql();
        return $string;
    }

    /**
     * @return string
     */
    public function parse(): string{
        return $this->__toString();
    }

    public function getBinds(): array
    {
        $nativeBinds = $this->nativeObject->getBinds();
        return array_merge(parent::getBinds(), $nativeBinds);
    }


}