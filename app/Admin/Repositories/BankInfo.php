<?php

namespace App\Admin\Repositories;

use App\Models\BankInfo as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class BankInfo extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
