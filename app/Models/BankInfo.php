<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
	use HasDateTimeFormatter;
    protected $table      = 'bank_infos';
    protected $connection = 'mysql_book';

    public function withdrawOrder()
    {
        return $this->belongsTo(WithdrawOrder::class);
    }
}
