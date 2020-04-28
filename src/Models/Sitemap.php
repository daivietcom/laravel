<?php namespace Models;

use Illuminate\Database\Eloquent\Model;

class Sitemap extends Model{

    protected $table = 'sitemaps';
    protected $fillable = ['uri'];
    public $timestamps = false;

    protected $dates = [
        'updated_at'
    ];

}
