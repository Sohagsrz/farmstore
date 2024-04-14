<?php 
use App\Models\CustomFields;
use App\Models\CustomOptions;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// test function
function test() {
    return 'test';
}
// get db obj
function db()
{
    return DB::class;
}




/**
 * Check if a string is serialized.
 *
 * @param string $data The string to check.
 *
 * @return bool True if serialized, false otherwise.
 */
function is_serialized($data)
{
    // If it's not a string, it's not serialized
    if (!is_string($data)) {
        return false;
    }

    // Trim whitespaces
    $data = trim($data);

    // If it starts with 'a:', 's:', 'i:', or 'O:', it might be serialized
    if (in_array(substr($data, 0, 2), ['a:', 's:', 'i:', 'O:'], true)) {
        return true;
    }

    // If it starts with 'N;' or 'b:', it might be serialized
    if (in_array(substr($data, 0, 2), ['N;', 'b:'], true) && unserialize($data) !== false) {
        return true;
    }

    return false;
}


function serializeIfNeeded($variable) {
    // Check if the variable is an array or an object
    if (is_array($variable) || is_object($variable)) {
        // Serialize arrays and objects
        return serialize($variable);
    } else {
        // Return the variable as it is
        return $variable;
    }
}
// get field data
function get_field($name, $type, $obj_id, $default = '')
{
    return CustomFields::getField($name, $type, $obj_id, $default);
}

// add new field
function update_field($name, $value, $type, $obj_id)
{
    CustomFields::addField($name, $value, $type, $obj_id);
}

// get option
function get_option($name, $default = '')
{
    return CustomOptions::getOption($name, $default);
}

//update option
function update_option($name, $value)
{
    CustomOptions::setOption($name, $value);
}

// get user current role
function get_role($user_id)
{
    return User::find($user_id)->getRole();
}
