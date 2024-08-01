<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 16.04.2019
 * Time: 23:16
 */

namespace Siarko\SqlCreator\DatabaseElement;

use Siarko\DependencyManager\DependencyManager;
use Siarko\SqlCreator\DatabaseElement\Type\AbstractDataType;
use Siarko\SqlCreator\Exceptions\UnknownDataTypeException;

class DataTypeProvider
{

    /**
     * @var array
     */
    protected array $types = [];

    public function __construct()
    {
        //numeric
        $this->addType(['int'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\Integer');
        $this->addType(['tinyint', 'bool', 'boolean'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\TinyInt');
        $this->addType(['smallint'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\SmallInt');
        $this->addType(['mediumint'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\MediumInt');
        $this->addType(['bigint'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\BigInt');
        $this->addType(['decimal', 'dec', 'numeric', 'fixed'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\Decimal');
        $this->addType(['float'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\FloatType');
        $this->addType(['double'], '\Siarko\SqlCreator\DatabaseElement\Type\Numeric\Double');
        //dates
        $this->addType(['date'], '\Siarko\SqlCreator\DatabaseElement\Type\DateTime\Date');
        $this->addType(['datetime'], '\Siarko\SqlCreator\DatabaseElement\Type\DateTime\DateTime');
        $this->addType(['time'], '\Siarko\SqlCreator\DatabaseElement\Type\DateTime\Time');
        $this->addType(['timestamp'], '\Siarko\SqlCreator\DatabaseElement\Type\DateTime\TimeStamp');
        $this->addType(['year'], '\Siarko\SqlCreator\DatabaseElement\Type\DateTime\Year');
        //text
        $this->addType(['blob'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\Blob');
        $this->addType(['char'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\Char');
        $this->addType(['longblob'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\LongBlob');
        $this->addType(['longtext'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\LongText');
        $this->addType(['mediumblob'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\MediumBlob');
        $this->addType(['mediumtext'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\MediumText');
        $this->addType(['text'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\Text');
        $this->addType(['varchar'], '\Siarko\SqlCreator\DatabaseElement\Type\Text\Varchar');
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param string[] $types
     */
    public function setTypes(array $types): static
    {
        $this->types = $types;
        return $this;
    }

    /**
     * @param string[] $names
     * @param string $type
     * @return $this
     */
    public function addType(array $names, string $type): static
    {
        $this->types[] = [$names, $type];
        return $this;
    }

    /**
     * @param string $name
     * @return string
     * @throws UnknownDataTypeException
     */
    protected function getTypeName(string $name): string
    {
        $value = array_filter($this->types, function($value) use ($name){
            return in_array($name, $value[0]);
        });
        if(count($value) == 1){
            return current($value)[1];
        }
        throw new UnknownDataTypeException($name);
    }

    /**
     * @param string $data
     * @return AbstractDataType
     * @throws UnknownDataTypeException
     */
    public function parseString(string $data): AbstractDataType
    {
        $result = [];
        preg_match("#(?<typeName>[a-z]+)(\((?<length>(\d*),?\d*)\))?#", $data, $result);
        $length = array_key_exists('length', $result) ? $result['length'] : null;
        return $this->getTypeInstance($this->getTypeName($result['typeName']), $length);
    }

    /**
     * @param string $type
     * @param string|null $length
     * @return AbstractDataType
     */
    protected function getTypeInstance(string $type, ?string $length): AbstractDataType
    {
        if($length === null){
            return new $type();
        }
        return new $type(length: $length);
    }

}