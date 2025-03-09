<?php

namespace App\Database\Repositories;

use App\Models\Subject;
class SubjectRepository extends Repository
{
    protected function getModelClass(): string {
        return Subject::class;
    }
    protected static $table = 'subjects';

}