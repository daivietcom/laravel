<?php namespace Models;

use Illuminate\Database\Eloquent\Model;
use VnSource\Traits\ExtendTrait;
use Auth;

class Media extends Model
{
    use ExtendTrait;

    protected $table = 'medias';

    protected $extendColumnName = 'extend';

    protected $casts = [
        'extend' => 'array'
    ];

    protected $hidden = ['extend'];

    protected $columnName = ['id', 'name', 'source', 'thumb', 'type', 'mime', 'size', 'width', 'height', 'folder', 'extend', 'model', 'disk', 'md5', 'sha1', 'created_at', 'updated_at', 'created_by', 'updated_by'];


    public static function boot()
    {
        static::updating(function ($model)
        {
            $model->updated_by = Auth::user()->id;
        });
        static::creating(function ($model)
        {
            $model->created_by = Auth::user()->id;
            $model->updated_by = Auth::user()->id;
        });
    }

    public function createUser()
    {
        return $this->belongsTo('Models\User', 'created_by');
    }

    public function updateUser()
    {
        return $this->belongsTo('Models\User', 'updated_by');
    }

    public function folderSize()
    {
        return 4;
    }

    protected function childs() {
        return $this->hasMany('Models\Media', 'folder')
            ->select('type')
            ;
    }

    public function countFiles()
    {
        return count(array_where($this->childs->toArray(), function($key, $value) {
            return $value['type'] != 'folder';
        }));
    }

    public function countFolders()
    {
        return count(array_where($this->childs->toArray(), function($key, $value) {
            return $value['type'] == 'folder';
        }));;
    }
}
