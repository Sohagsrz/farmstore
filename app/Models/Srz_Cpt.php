<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUniqueSlugTrait;

class Srz_Cpt extends Model
{
    use HasFactory;
    use GenerateUniqueSlugTrait;
    protected $table = 'srz_cpt';
    protected $fillable = ['post_title', 'post_content', 'post_type', 'post_slug'];
    
    
    public function get_all_cpt()
    {
        return $this->all();
    }

    public function get_cpt($id)
    {
        return $this->find($id);
    }

    public function add_cpt($data)
    {
        return $this->create($data);
    }

    public function update_cpt($id, $data)
    {
        return $this->find($id)->update($data);
    }
    // detaching categories all without ids
    public function detach_categories($id)
    {
        return $this->find($id)->categories()->detach();
    }
    
    public function delete_cpt($id)
    {
        return $this->find($id)->delete();
    }
    //CATEGORIES    

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_relation', 'post_id', 'category_id');
    }

    public function get_categories()
    {
        return $this->categories;
    }

    public function add_category($category)
    {
        return $this->categories()->attach($category);
    }

    public function remove_category($category)
    {
        return $this->categories()->detach($category);
    }

    public function has_category($category)
    {
        return $this->categories->contains($category);
    }
    // default route
    public function getRouteKeyName()
    {
        return 'post_slug';
    }


}
