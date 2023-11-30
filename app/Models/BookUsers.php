<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookUsers extends Model
{
    public $table         = 'users';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
