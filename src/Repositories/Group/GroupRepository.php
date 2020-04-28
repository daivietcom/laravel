<?php namespace Repositories\Group;

use Repositories\EloquentRepository;

class GroupRepository extends EloquentRepository implements GroupRepositoryInterface
{

    public function getModel()
    {
        return \Models\Group::class;
    }

    public function getAllWithUsers()
    {
        return $this->_model->leftJoin('users', 'groups.code', '=', 'users.group_code')
            ->groupBy('groups.code')
            ->select('groups.*', \DB::raw('count(users.id) as users'))
            ->get()
        ;
    }
}