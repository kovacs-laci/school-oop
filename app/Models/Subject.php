<?php

namespace App\Models;

use App\Database\Repositories\SubjectRepository;

class Subject extends Model
{
    public $name;

    protected function getModelClass(): string {
        return Subject::class;
    }
    protected static $table = 'subjects';

    public function __construct($name = null)
    {
        parent::__construct();
        if ($name) {
            $this->name = $name;
        }

    }
}
