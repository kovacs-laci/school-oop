<?php

namespace App\Database\Repositories;

use App\Database\Database;

class StudentRepository extends Repository
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = 'students';
    }
}