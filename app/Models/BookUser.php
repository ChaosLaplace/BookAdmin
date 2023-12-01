<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
	use HasDateTimeFormatter;
    protected $table      = 'users';
    protected $connection = 'mysql_book';
}
