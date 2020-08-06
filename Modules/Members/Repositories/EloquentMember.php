<?php namespace Modules\Members\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\RepositoriesAbstract;

class EloquentMember extends RepositoriesAbstract implements MemberInterface
{

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}