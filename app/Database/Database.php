<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Views\Display;

class Database
{
    private const HOST = 'localhost';
    private const USER = 'root';
    private const PASSWORD = null;
    private const DATABASE = 'school';
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct(
        $host = self::HOST,
        $user = self::USER,
        $password = self::PASSWORD,
        $database = self::DATABASE
    ) {
        try {
            $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exception mode
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch as associative array
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            die("Database connection error, please try again later.");
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function execSql(string $sql, array $params = []): bool|int|array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            // Handle INSERT (return last insert ID)
            if (str_starts_with(strtoupper(trim($sql)), 'INSERT')) {
                return (int) $this->pdo->lastInsertId();
            }

            // Handle SELECT (return results)
            if (str_starts_with(strtoupper(trim($sql)), 'SELECT')) {
                return $stmt->fetchAll() ?: [];
            }

            // Handle UPDATE / DELETE
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());
            return false;
        }
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
}
