<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
	use HasDateTimeFormatter;    protected $primaryKey = 'book_id';
	
    protected $connection = 'mysql_book';
}
