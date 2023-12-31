<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class UserPayRecord extends Model
{
	use HasDateTimeFormatter;
    protected $table      = 'user_pay_records';
    protected $connection = 'mysql_book';
}
