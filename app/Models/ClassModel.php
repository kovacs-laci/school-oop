<?php

namespace App\Models;

class ClassModel extends Model
{
    public string|null $code = null;

    public int|null $year = null;

    protected static $table = 'subjects';

    public function __construct(?string $code = null, ?int $year = null)
    {
        parent::__construct();
        if ($code) {
            $this->code = $code;
        }

        if ($year) {
            $this->year = $year;
        }

    }
}
