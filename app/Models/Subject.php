<?php

namespace App\Models;

use App\Database\Repositories\SubjectRepository;

class Subject extends Model
{
    public $name;

    public function __construct(SubjectRepository $repository)
    {
        parent::__construct($repository);
    }

    

//    function save()
//    {
//        return $this->repository->insert(['name' => $this->name]);
//    }



}
