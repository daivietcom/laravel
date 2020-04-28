<?php namespace Repositories;


interface RepositoryInterface
{

    public function filter(array $attributes);

    public function getAll();

    public function getCount();

    public function find($id);

    public function create(array $attributes);

    public function insert(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);
}