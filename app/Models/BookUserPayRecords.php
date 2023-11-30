<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookUserPayRecords extends Model
{
    public $table         = 'user_pay_records';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
