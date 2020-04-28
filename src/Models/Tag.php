<?php namespace Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['name', 'slug'];
    public $timestamps = false;

    public function scopeFindBySlug($query, $slug) {
        return $query->where('slug' ,'=', $slug)
            ->first();
    }
}
