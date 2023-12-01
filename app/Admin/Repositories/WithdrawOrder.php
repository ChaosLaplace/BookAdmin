<?php

namespace App\Admin\Repositories;

use App\Models\WithdrawOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class WithdrawOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
