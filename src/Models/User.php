<?php namespace Models;

use VnSource\Traits\ExtendTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, ExtendTrait;

    protected $table = 'users';

    protected $fillable = ['phone', 'email', 'display_name', 'group_code', 'status', 'password', 'api_token'];

    protected $hidden = ['password', 'remember_token', 'extend', 'api_token'];

    protected $casts = [
        'money' => 'array',
        'social' => 'array',
        'option' => 'array',
        'extend' => 'array',
        'status' => 'boolean'
    ];

    protected $extendColumnName = 'extend';

    protected $columnName = ['id', 'email', 'phone', 'password', 'display_name', 'status', 'group_code', 'money', 'social', 'option', 'extend', 'remember_token', 'api_token', 'created_at', 'updated_at'];

    protected $lockDelete = [1];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            if ($model->userExist()) return false;
            if (empty($model->api_token)) {
              $model->api_token = str_random(60);
            }
        });
        static::deleting(function ($model)
        {
            if (in_array($model->id, $model->lockDelete)) return false;
        });
    }

    protected function userExist() {
        $email = $this->email;
        $phone = $this->phone;
        return static::where(function($query) use ($email)
        {
            if(!empty($email)) {
                $query->where('email', '=', $email);
            }
        })->orWhere(function($query) use ($phone)
        {
            if(!empty($phone)) {
                $query->where('phone', '=', $phone);
            }
        })->count() > 0;
    }

    public function emailExist($email) {
        return static::where('email', '=', $email)->count() > 0;
    }

    public function phoneExist($phone) {
        return static::where('phone', '=', $phone)->count() > 0;
    }

    public function group()
    {
        return $this->belongsTo('Models\Group', 'group_code');
    }

    public function permissions()
    {
        if ($this->group->status == false || empty($this->group->permissions)) return [];
        return $this->group->permissions;
    }

    public function hasGroup($code)
    {
        return $this->group_code == $code;
    }

    public function can($permission, $requireAll = false)
    {
        if($this->status == false) return false;

        if($this->hasGroup('administrator')) return true;

        if(is_array($permission)) {
            foreach($permission as $perCode) {
                $canPerm = $this->can($perCode);
                if ($canPerm && !$requireAll) {
                    return true;
                }
                elseif (!$canPerm && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        }
        else {
            $thisPermission = $this->permissions();
            if (empty($thisPermission)) return false;
            return in_array($permission, $thisPermission);
        }
    }
}
