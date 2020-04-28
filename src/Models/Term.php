<?php namespace Models;

use VnSource\Traits\ExtendTrait;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use ExtendTrait;
    protected $table = 'terms';
    protected $casts = [
        'extend' => 'array',
        'status' => 'boolean'
    ];

    protected $extendColumnName = 'extend';

    protected $hidden = ['extend'];

    protected $columnName = ['id', 'name', 'slug', 'description', 'image', 'order', 'parent', 'extend', 'status', 'created_at', 'updated_at', 'model'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            $model->model = get_called_class();
        });
    }

    public function scopeByOrder($query) {
        return $query->orderBy('terms.order', 'asc');
    }

    public function scopeFindSlug($query, $slug) {
        return $query->where('terms.slug', $slug)
            ->first()
         ;
    }

    public function newQuery($excludeDeleted = true) {
        $class = get_called_class();
        $query = parent::newQuery($excludeDeleted);
        if ($class != 'Models\Term') {
            $query->where($this->table.'.model', '=', $class);
        }
        return $query;
    }
}
