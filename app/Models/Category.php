<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $fillable = ['name', 'slug'];
    public $timestamps = false;

    public function posts()
    {
        return $this->belongsToMany(Srz_Cpt::class, 'category_relation', 'category_id', 'post_id');
    }
    // route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // custom fields for category, with name & value, type=category and obj_id = category_id
    public function custom_fields()
    {
        return $this->hasMany(CustomFields::class, 'obj_id', 'id')->where('type', 'category');
    }

    
    
 

}
