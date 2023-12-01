<?php

namespace App\Admin\Repositories;

use App\Models\BookIncome as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class BookIncome extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
