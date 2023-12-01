<?php

namespace App\Admin\Repositories;

use App\Models\BookUser as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class BookUser extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
