<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_Relation extends Model
{
    use HasFactory;
    protected $table = 'category_relation';
    protected $fillable = ['post_id', 'category_id'];
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Srz_Cpt::class, 'post_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function get_all_relations()
    {
        return $this->all();
    }

    public function get_relation($id)
    {
        return $this->find($id);
    }
    
}
