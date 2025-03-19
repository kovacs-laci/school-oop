<?php

namespace App\Models;

class Subject extends Model
{
    public string|null $name = null;

    protected static $table = 'subjects';

    public function __construct(?string $name = null)
    {
        parent::__construct();
        if ($name) {
            $this->name = $name;
        }

    }
}
