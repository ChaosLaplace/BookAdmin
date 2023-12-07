<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class FileComparison extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'file_comparison';
    
}
