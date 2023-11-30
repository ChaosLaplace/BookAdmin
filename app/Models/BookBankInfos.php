<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookBankInfos extends Model
{
    public $table         = 'bank_infos';
    public $timestamp     = false;
    protected $connection = 'mysql_book';
}
