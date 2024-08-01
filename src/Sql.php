<?php
/**
 * Created by PhpStorm.
 * User: SiarkoWodór
 * Date: 10.08.2018
 * Time: 21:27
 */

namespace Siarko\SqlCreator;

use JetBrains\PhpStorm\Pure;

class Sql {

    /**
     * @param $columns
     * @return Select
     */
    public static function select($columns): Select
    {
        return new Select($columns);
    }

    /**
     * @param array $data
     * @param InsertMode $mode
     * @return Insert
     */
    public static function insert(array $data, InsertMode $mode = InsertMode::ASSOC): Insert
    {
        return new Insert($data, $mode);
    }

    /**
     * @return Delete
     */
    #[Pure] public static function delete(): Delete
    {
        return new Delete();
    }

    /**
     * @param string|array $tables
     * @return Update
     */
    public static function update(string|array $tables): Update
    {
        return new Update($tables);
    }

    /**
     * @param string $what
     * @return Show
     */
    public static function show(string $what): Show
    {
        return new Show($what);
    }
}