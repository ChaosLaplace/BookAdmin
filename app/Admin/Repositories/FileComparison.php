<?php

namespace App\Admin\Repositories;

use App\Models\FileComparison as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class FileComparison extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
