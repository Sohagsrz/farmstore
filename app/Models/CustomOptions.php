<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOptions extends Model
{
    use HasFactory;
    protected $table = 'custom_options';
    //get field value by field name , or get default
    public static function getOption($name, $default = '')
    {
        $option = CustomOptions::where('name', $name)->first();
        if ($option) { 
            return is_serialized($option->value) ? unserialize($option->value) : $option->value;
             
        } else {
            return $default;
        }
    }

    //set field value by field name
    public static function setOption($name, $value)
    {
        $option = CustomOptions::where('name', $name)->first();
        if ($option) {
            $option->value = serializeIfNeeded($value);
            $option->save();
        } else {
            $option = new CustomOptions;
            $option->name = $name;
            $option->value = serializeIfNeeded($value);
            $option->save();

           
        }
    }

}
