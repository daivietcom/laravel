<?php namespace Models;

use Auth;
use VnSource\Traits\ExtendTrait;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use ExtendTrait;
    protected $table = 'logs';
    protected $casts = [
        'content' => 'array'
    ];

    protected $extendColumnName = 'content';

    protected $columnName = ['id', 'content', 'created_at', 'created_by', 'model'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            $model->model = get_called_class();
            $model->created_by = Auth::user()->id;
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function newQuery($excludeDeleted = true) {
        $class = get_called_class();
        $query = parent::newQuery($excludeDeleted);
        if ($class != 'Models\Log') {
            $query->where($this->table.'.model', '=', $class);
        }
        return $query;
    }
}
