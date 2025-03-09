<?php

namespace App\Database\Repositories;

use App\Database\Database;
use App\Models\Model;

abstract class Repository {
    protected $db;
    protected static $table;

    abstract protected function getModelClass(): string;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    function mapToModel(array $data): Model {
        $modelClass = $this->getModelClass();
        $model = new $modelClass($this);

        foreach ($data as $key => $value) {
            if (property_exists($model, $key)) {
                $model->$key = $value;
            }
        }

        return $model;
    }
    static function select(): string
    {
        return "SELECT * FROM `" . static::$table . "` ";
    }

    function getAll(array $orderConfig = []): array
    {
        $sql = self::select();

        if (!empty($orderConfig)) {
            $orderByClauses = [];

            // Extract 'orderBy' and 'direction' fields
            $fields = $orderConfig['orderBy'] ?? [];
            $directions = $orderConfig['order'] ?? [];

            foreach ($fields as $index => $field) {
                // Use the corresponding direction or default to 'ASC'
                $direction = $directions[$index] ?? 'ASC';
                $orderByClauses[] = "$field $direction";
            }

            if (!empty($orderByClauses)) {
                $sql .= " ORDER BY " . implode(', ', $orderByClauses) . ";";
            }
        }

        $qryResult = $this->db->execSql($sql);

        if (empty($qryResult)) {
            return [];
        }

        $results = [];
        foreach ($qryResult as $row) {
            $results[] = $this->mapToModel($row);
        }

        return $results;
    }


    public function findOne($id): ?Model
    {
        $sql = self::select() . " WHERE id = :id";

        $qryResult = $this->db->execSql($sql, ['id' => $id]);
        if (empty($qryResult)) {
            return null;
        }

        return $this->mapToModel($qryResult[0]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `" . static::$table . "` WHERE id = :id";

        return $this->db->execSql($sql, ['id' => $id]);
    }

    public function insert(Model $model): int
    {
        $properties = get_object_vars($model);
        unset($properties['id']); // Exclude 'id' if it is auto-incremented

        $columns = implode(', ', array_keys($properties));

        $placeholders = [];
        foreach (array_keys($properties) as $key) {
            $placeholders[] = ":$key";
        }
        $placeholders = implode(', ', $placeholders);

        $sql = "INSERT INTO `" . static::$table . "` ($columns) VALUES ($placeholders)";

        return $this->db->execSql($sql, $properties);
    }

    public function update(Model $model): bool
    {
        $properties = get_object_vars($model);
        $id = $properties['id'] ?? null;

        if (!$id) {
            throw new \Exception("Cannot update a record without an ID.");
        }

        unset($properties['id']); // Exclude 'id' for the update values

        $setClauseParts = [];
        foreach (array_keys($properties) as $key) {
            $setClauseParts[] = "$key = :$key";
        }
        $setClause = implode(', ', $setClauseParts);

        $sql = "UPDATE `" . static::$table . "` SET $setClause WHERE id = :id";

        $properties['id'] = $id; // Add 'id' back for the WHERE clause

        return $this->db->execSql($sql, $properties);
    }

}
