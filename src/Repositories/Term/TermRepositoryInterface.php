<?php namespace Repositories\Term;


interface TermRepositoryInterface
{
    public function getAllOrder();
    public function loop($parent, $level = 0);
}