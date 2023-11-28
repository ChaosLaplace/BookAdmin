<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
    public $table         = 'users';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
