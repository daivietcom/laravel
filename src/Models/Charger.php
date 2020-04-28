<?php namespace Models;

use VnSource\Traits\ExtendTrait;
use Uuid;
use Illuminate\Database\Eloquent\Model;

class Charger extends Model{
    use ExtendTrait;

    protected $table = 'chargers';

    protected $casts = [
        'extend' => 'array'
    ];

    protected $extendColumnName = 'extend';

    protected $hidden = ['extend'];

    protected $columnName = ['id', 'status', 'amount', 'type', 'extend', 'user_id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
             if(empty($model->id)) {
                 $model->id = Uuid::generate();
             }
        });
    }

    public function scopeWhereUser($query, $id) {
        return $query->where('user_id' ,'=', $id);
    }
}
