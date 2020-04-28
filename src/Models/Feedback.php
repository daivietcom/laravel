<?php namespace Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    public $timestamps = false;

    protected $casts = [
        'author' => 'array'
    ];

    protected $dates = [
        'created_at'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            $model->model = get_called_class();
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function newQuery($excludeDeleted = true) {
        $class = get_called_class();
        $query = parent::newQuery($excludeDeleted);
        if ($class != 'Models\Feedback') {
            $query->where($this->table.'.model', '=', $class);
        }
        return $query;
    }
}
