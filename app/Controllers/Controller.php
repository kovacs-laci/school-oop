<?php

namespace App\Controllers;

use App\Database\Repositories\Repository;
use App\Models\Model;

abstract class Controller
{
//    protected Model $model;
//    public function __construct(Model $model)
//    {
//        $this->model = $model;
//    }
    protected Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    abstract function index();
    abstract function show(int $id);
    abstract function create();
    abstract function save(array $data);
    abstract function edit(int $id);
    abstract function update(int $id, array $data);
    abstract function delete(int $id);
//    abstract function search();
}