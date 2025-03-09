<?php

namespace App\Database\Repositories;

class ClassRepository extends Repository
{
    protected static $table = 'classes';
    /**
     * @var int
     */
    public int $year;
    /**
     * @var string
     */
    public string $code;


    function getYears(): array|bool|int
    {
        $sql = "SELECT DISTINCT year FROM " .  static::$table;

        $result =  $this->db->execSql($sql);

        return $result;
    }

    function getByCodeAndYear($classCode, $year)
    {
        $sql = self::select() . "WHERE `code` = ':code' AND `year` = ':year';";

        return $this->db->execSql($sql, ['code' => $classCode, 'year' => $year]);
    }

}