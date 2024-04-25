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
    //custom fields for user, get with name & value, type=user and obj_id = user_id
    public function custom_fields()
    {
        return $this->hasMany(CustomFields::class, 'obj_id', 'id')->where('type', $this->post_type)->select('name', 'value')->get();
    }
    // set custom field
    public function setCustomField($name, $value)
    {
        update_field(
            $name,
            $value,
            $this->post_type,
            $this->id
        );
    
    }
    // get field by key
    public function getCustomField($name)
    {
        return get_field($name, $this->post_type, $this->id);
    }
    // get user
    public function user()
    {
        return $this->belongsTo(User::class, 'post_author', 'id');
    }



}
