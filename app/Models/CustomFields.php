<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFields extends Model
{
    use HasFactory;
    protected $table = 'custom_fields';
    protected $fillable = ['name', 'value', 'type', 'obj_id'];
    // add new field if exists update
    public static function addField($name, $value, $type, $obj_id)
    {
        $field = CustomFields::where('name', $name)->where('type', $type)->where('obj_id', $obj_id)->first();
        if ($field) {
            $field->value = serializeIfNeeded($value);
            $field->save();
        } else {
            $field = new CustomFields;
            $field->name = $name;
            $field->value = serializeIfNeeded($value);
            $field->type = $type;
            $field->obj_id = $obj_id;
            $field->save();
        }
    }

    //get field value by field name , or get default
    public static function getField($name, $type, $obj_id, $default = '')
    {
        $field = CustomFields::where('name', $name)->where('type', $type)->where('obj_id', $obj_id)->first();
        if ($field) {
            return is_serialized($field->value) ? unserialize($field->value) : $field->value;
        } else {
            return $default;
        }
    }   


}
