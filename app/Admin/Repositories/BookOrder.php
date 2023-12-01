<?php

namespace App\Admin\Repositories;

use App\Models\BookOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class BookOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
