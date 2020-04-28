<?php namespace Repositories\User;


interface UserRepositoryInterface
{

    public function findEmail($email);

    public function search($search, $select = '*', $limit = 10);
}