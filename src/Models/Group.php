<?php namespace Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'code';
    protected $lock = ['administrator','member'];
    protected $fillable = ['code', 'name', 'permissions', 'status', 'description'];
    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        'permissions' => 'array',
        'status' => 'boolean'
    ];

    public static function boot()
    {
        static::deleting(function ($model)
        {
            if (in_array($model->code, $model->lock)) return false;
        });
    }

    public function users() {
        return $this->hasMany('Models\User', 'group_code');
    }
}
