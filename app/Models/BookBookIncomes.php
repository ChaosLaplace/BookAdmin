<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookBookIncomes extends Model
{
    public $table         = 'book_incomes';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
