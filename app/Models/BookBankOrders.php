<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookBankOrders extends Model
{
    public $table         = 'book_orders';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
