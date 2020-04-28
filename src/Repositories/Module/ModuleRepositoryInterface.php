<?php namespace Repositories\Module;


interface ModuleRepositoryInterface
{
    public function getAll($type);
    public function store($name);
    public function update($id, array $attributes);
    public function check($github);
    public function download($name);
    public function unpack($name, $version);

}