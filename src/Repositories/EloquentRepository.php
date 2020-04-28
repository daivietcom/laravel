<?php namespace Repositories;

abstract class EloquentRepository implements RepositoryInterface
{

    protected $_model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel()
    {
        $this->_model = app($this->getModel());
    }

    public function filter(array $attributes)
    {
        $count = empty($attributes['count'])?10:$attributes['count'];
        $page = empty($attributes['page'])?1:$attributes['page'];
        $offset = $count * ($page - 1);
        $total = -1;

        $query = $this->_model->where(function ($q) use ($attributes) {
            if (isset($attributes['filter'])) {
                foreach ($attributes['filter'] as $key => $filter) {
                    if ($key == 'status') {
                        $q->where('status', filter_var($filter, FILTER_VALIDATE_BOOLEAN));
                    } else {
                        $q->where($key, 'like', "%$filter%");
                    }
                }
            }
        });
        if (isset($attributes['sorting'])) {
            foreach ($attributes['sorting'] as $key => $val) {
                $query->orderBy($key, $val);
            }
        }
        if($count != -1) {
            $query->limit($count)
                ->offset($offset)
            ;
            $total = $this->_model->where(function ($q) use ($attributes) {
                if (isset($attributes['filter'])) {
                    foreach ($attributes['filter'] as $key => $filter) {
                        if ($key == 'status') {
                            $q->where('status', filter_var($filter, FILTER_VALIDATE_BOOLEAN));
                        } else {
                            $q->where($key, 'like', "%$filter%");
                        }
                    }
                }
            })->count();
        }

        return [$query->get(), $total];
    }

    public function getAll()
    {
        return $this->_model->all();
    }

    public function getCount()
    {
        return $this->_model->count();
    }

    public function find($id)
    {
        $result = $this->_model->find($id);
        return $result;
    }

    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    public function insert(array $attributes)
    {
        return $this->_model->insert($attributes);
    }

    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if($result) {
            if (isset($attributes['toggleStatus'])) {
                $attributes = [
                    'status' => $attributes['status']
                ];
            }

            $result->update($attributes);
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if($result) {
            $result->delete();
            return true;
        }

        return false;
    }
}