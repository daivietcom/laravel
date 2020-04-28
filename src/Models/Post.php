<?php namespace Models;

use Auth;
use VnSource\Traits\ExtendTrait;
use Illuminate\Database\Eloquent\Model;

class Post extends Model{
    use ExtendTrait;

    protected $table = 'posts';
    protected $dates = [
        'created_at',
        'updated_at',
        'publish_at',
        'suppress_at'
    ];

    protected $casts = [
        'comment' => 'boolean',
        'status' => 'boolean',
        'extend' => 'array',
        'categories' => 'array',
        'on_process' => 'boolean'
    ];

    protected $extendColumnName = 'extend';

    protected $hidden = ['extend'];

    protected $columnName = ['on_process', 'total_chap', 'id', 'name', 'slug', 'title', 'excerpt', 'content', 'image', 'status', 'comment', 'categories', 'parent', 'extend', 'viewed', 'publish_at', 'suppress_at', 'created_at', 'updated_at', 'created_by', 'updated_by', 'model', 'viewed'];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model)
        {
            if(($model->status == 'publish' || empty($model->status)) AND empty($model->publish_at)) {
                $model->publish_at = $model->freshTimestamp();
            }
        });
        static::updating(function ($model)
        {
            $model->updated_by = Auth::user()->id;
        });
        static::creating(function ($model)
        {
            if ($model->slugExist()) return false;
            $model->model = get_called_class();
            $model->created_by = Auth::user()->id;
            $model->updated_by = Auth::user()->id;
        });
    }

    public function slugExist($slug = null) {
        if (is_null($slug)) $slug = $this->slug;
        return static::where('slug', '=', $slug)->count() > 0;
    }

    public function category()
    {
        return $this->belongsTo('Models\Term', 'parent');
    }

    public function createdBy()
    {
        return $this->belongsTo('Models\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('Models\User', 'updated_by');
    }

    public function newQuery($excludeDeleted = true) {
        $class = get_called_class();
        $query = parent::newQuery($excludeDeleted);
        if ($class != 'Models\Post') {
            $query->where($this->table.'.model', '=', $class);
        }
        return $query;
    }
}
