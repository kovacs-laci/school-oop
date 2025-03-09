<?php

namespace App\Models;

use App\Database\Repositories\Repository;

abstract class Model
{
    public int $id;

//    protected Repository $repository;

//    public function __construct(Repository $repository)
//    {
//        $this->repository = $repository;
//    }

    function find(int $id): ?static
    {
        return $this->repository->findOne($id);

//        return $result ? $this->fromArray($result[0]) : null;
    }

    function all($orderBy = []): array
    {
        return $this->repository->getAll($orderBy = []);
    }

    function delete()
    {
        return $this->repository->delete($this->id);
    }

    public function save(): int
    {
        // Check if the model already exists (based on `id`)
        if (isset($this->id)) {
            // Call the repository's update method for existing records
            return $this->repository->update($this);
        }
        // Call the repository's insert method for new records
        $insertedId = $this->repository->insert($this);
        $this->id = $insertedId; // Set the id to the inserted record's ID
        return $insertedId;

    }
}
