<?php namespace Models;

use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    protected $table = 'counts';

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            $model->model = get_called_class();
        });
    }

    public function newQuery($excludeDeleted = true) {
        $class = get_called_class();
        $query = parent::newQuery($excludeDeleted);
        if ($class != 'Models\Count') {
            $query->where($this->table.'.model', '=', $class);
        }
        return $query;
    }
}
