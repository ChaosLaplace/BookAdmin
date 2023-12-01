<?php

namespace App\Admin\Repositories;

use App\Models\UserPayRecord as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UserPayRecord extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
