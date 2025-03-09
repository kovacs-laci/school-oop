<?php

namespace App\Database;

use App\Views\Display;
use Exception;

class Install extends Database
{

    function dbExists(): bool
    {
        try {
            $mysqli = getConn('mysql');
            if (!$mysqli) {
                return false;
            }

            $query = sprintf("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s';", self::DEFAULT_CONFIG['database']);
            $result = $mysqli->query($query);

            if (!$result) {
                throw new Exception('Lekérdezési hiba: ' . $mysqli->error);
            }
            $exists = $result->num_rows > 0;

            return $exists;

        }
        catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());

            return false;
        }
        finally {
            // Ensure the database connection is always closed
            $mysqli?->close();
        }

    }

    public function createTable(string $tableName, string $tableBody, string $dbName): bool
    {
        try {
            $sql = "
                CREATE TABLE `$dbName`.`$tableName`
                ($tableBody)
                ENGINE = InnoDB
                DEFAULT CHARACTER SET = utf8
                COLLATE = utf8_hungarian_ci;
            ";
            return (bool) $this->execSql($sql);

        } catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());
            return false;
        }
    }

    function createTableSubjects($dbName = self::DEFAULT_CONFIG['database']): bool
    {
        $tableBody = "
            `id` INT NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(15) NOT NULL,
            PRIMARY KEY (`id`)
        ";

        return createTable($tableBody, 'subjects',  $dbName);
    }
    function createTableClasses($dbName = self::DEFAULT_CONFIG['database']): bool
    {
        $tableBody = "
            `id` INT NOT NULL AUTO_INCREMENT,
            `code` VARCHAR(5) NOT NULL,
            `year` INT NOT NULL,
            PRIMARY KEY (`id`)";
        return createTable($tableBody, "classes", $dbName);
    }

    function createTableStudents($dbName = self::DEFAULT_CONFIG['database']): bool
    {
        $tableBody = "
            `id` INT NOT NULL AUTO_INCREMENT,
            `class_id` INT NOT NULL,
            `name` VARCHAR(50) NOT NULL,
            `gender` TINYINT NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`class_id`) REFERENCES classes(`id`)";

        return createTable($tableBody, "students", $dbName);
    }

    function createTableMarks($dbName = self::DEFAULT_CONFIG['database']): bool
    {
        $tableBody = "
            `id` INT NOT NULL AUTO_INCREMENT,
            `student_id` INT NOT NULL,
            `subject_id` INT NOT NULL,
            `mark` INT NOT NULL,
            `date` DATE NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`student_id`) REFERENCES students(`id`),
            FOREIGN KEY (`subject_id`) REFERENCES subjects(`id`)
        ";

        return createTable($tableBody, "marks", $dbName);
    }
}