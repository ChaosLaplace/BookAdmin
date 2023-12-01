<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class WithdrawOrder extends Model
{
	use HasDateTimeFormatter;
    protected $table      = 'withdraw_orders';
    protected $connection = 'mysql_book';
}
