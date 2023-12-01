<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class BookIncome extends Model
{
	use HasDateTimeFormatter;
    protected $table      = 'book_incomes';
    protected $connection = 'mysql_book';
}
