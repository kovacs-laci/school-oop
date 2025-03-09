<?php

namespace App\Database\Repositories;

use App\Database\Repositories\Repository;

class MarkRepository extends Repository
{
    public function __construct($tableName)
    {
        parent::__construct($tableName);
        $this->tableName = "marks";
    }
}