<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookWithdrawOrders extends Model
{
    public $table         = 'withdraw_orders';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
