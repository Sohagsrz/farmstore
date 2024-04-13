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
    // protected $fillable = ['cpt_code','cpt_description','cpt_price'];
    
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

    public function delete_cpt($id)
    {
        return $this->find($id)->delete();
    }
 

}
