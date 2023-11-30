<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookBookContents extends Model
{
    public $table         = 'book_contents';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
