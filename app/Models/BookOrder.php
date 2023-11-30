<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookOrder extends Model
{
    public $table         = 'order';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
