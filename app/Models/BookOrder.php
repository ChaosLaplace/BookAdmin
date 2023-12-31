<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class BookOrder extends Model
{
	use HasDateTimeFormatter;
    protected $table      = 'book_orders';
    protected $connection = 'mysql_book';
}
