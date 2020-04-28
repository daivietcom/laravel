<?php namespace Repositories\Term;

use Repositories\EloquentRepository;

abstract class TermRepository extends EloquentRepository implements TermRepositoryInterface
{
    public function getModel()
    {
        return \Models\Term::class;
    }

    public function getAllOrder()
    {
        return $this->_model->byOrder()
            ->get();
    }

    public function getCount($parent = null)
    {
        if ($parent === null) {
            return $this->_model->count();
        } else {
            return $this->_model->where('parent', $parent)->count();
        }
    }

    public function loop($parent, $level = 0) {
        $terms = $this->_model->where('parent', $parent)
            ->orderBy('order', 'asc')
            ->select('id', 'name', 'slug', 'parent', 'status')
            ->get()
            ->toArray()
        ;
        if(count($terms) == 0) {
            return [];
        } else {
            $_terms = [];
            foreach ($terms as $key => $term) {
                $term['space'] = $level;
                $_terms[] = $term;
                $childrens = $this->loop($term['id'], $level+1);
                $_terms = array_collapse([$_terms, $childrens]);
            }
            return $_terms;
        }
    }
}