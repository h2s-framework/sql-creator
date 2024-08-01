<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 08.05.2019
 * Time: 13:57
 */

namespace Siarko\SqlCreator;

use JetBrains\PhpStorm\Pure;
use Siarko\SqlCreator\DatabaseElement\Column;
use Siarko\SqlCreator\DatabaseElement\Creator\Constraint;
use Siarko\SqlCreator\DatabaseElement\Key\AbstractColumnKey;
use Siarko\SqlCreator\Language\Tokens\Token;

class AlterTable extends BasicQuery
{
    /**
     * @var Column[]
     */
    private array $addColumns = [];
    /**
     * @var Column[]
     */
    protected array $dropColumns = [];
    /**
     * @var Column[]
     */
    protected array $modifyColumns = [];

    /**
     * @var AbstractColumnKey[]
     */
    protected array $addKeys = [];

    protected array $dropKeys = [];

    /**
     * @param string $tableName
     */
    function __construct(private readonly string $tableName) {}

    /**
     * @param Column|AbstractColumnKey $data
     * @return $this
     */
    public function add(Column|AbstractColumnKey $data): static
    {
        if($data instanceof Column){
            $this->addColumns[] = $data;
        }else{
            $this->addKeys[] = $data;
        }
        return $this;
    }

    /**
     * @param Column|AbstractColumnKey $data
     * @return $this
     */
    public function drop(Column|AbstractColumnKey $data): static
    {
        if($data instanceof Column){
            $this->dropColumns[] = $data;
        }else{
            $this->dropKeys[] = $data;
        }
        return $this;
    }

    /**
     * @param Column $column
     * @return $this
     */
    public function modify(Column $column): static
    {
        $this->modifyColumns[] = $column;
        return $this;
    }

    /**
     * @return string
     */
    #[Pure] public function parse(): string
    {
        $implodeChar = ','.Token::SPACE->value;
        $sql = BasicQuery::tokenSequence([Token::ALTER, Token::TABLE]).$this->tableName.Token::SPACE->value;
        $modifierSql = implode($implodeChar, $this->constructModifiers());
        if(strlen(trim($modifierSql)) == 0){
            return '';
        }
        return $sql.$modifierSql;
    }

    /**
     * @return array|array[]
     */
    protected function constructModifiers(): array
    {
        $columnCreator = new \Siarko\SqlCreator\DatabaseElement\Creator\Column();
        $keyCreator = new Constraint();
        $result = [];
        foreach ($this->addColumns as $column) {
            $result[] = BasicQuery::tokenSequence([Token::ADD, Token::COLUMN]).$columnCreator->createSql($column);
        }
        foreach ($this->addKeys as $addKey) {
            $result[] = BasicQuery::tokenSequence([Token::ADD, Token::CONSTRAINT]).$keyCreator->createSql($addKey);
        }
        foreach ($this->dropColumns as $dropColumn) {
            $result[] = BasicQuery::tokenSequence([Token::DROP, Token::COLUMN]).$dropColumn->getName();
        }
        foreach ($this->modifyColumns as $modifyColumn) {
            $result[] = BasicQuery::tokenSequence([Token::MODIFY, Token::COLUMN]).$columnCreator->createSql($modifyColumn);
        }
        foreach ($this->dropKeys as $dropKey) {
            $result[] = $keyCreator->dropSql($dropKey);
        }
        return $result;
    }
}