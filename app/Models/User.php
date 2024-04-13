<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{ 

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

   
    public function roles()

    {
        return $this
            ->belongsToMany('App\\Models\Role')
            ->withTimestamps();
    }

    public function users()

    {
        return $this
            ->belongsToMany('App\Models\User')
            ->withTimestamps();
    }


    public function authorizeRoles($roles)

{
  if ($this->hasAnyRole($roles)) {
    return true;
  }
  abort(401, 'This action is unauthorized.');
}
public function hasAnyRole($roles)
{
  if (is_array($roles)) {
    foreach ($roles as $role) {
      if ($this->hasRole($role)) {
        return true;
      }
    }
  } else {
    if ($this->hasRole($roles)) {
      return true;
    }
  }
  return false;
}
public function hasRole($role)
{
  if ($this->roles()->where('name', $role)->first()) {
    return true;
  }
  return false;
}
// assing role to user
public function assignRole($role)
{
  // create a new role if not exists, or get the exits id
  $id = Role::firstOrCreate(['name' => $role],['description' => $role])->id;
   
  // add to user role if not exists , remove old role and add new role
  if(!$this->hasRole($role)){
    $this->roles()->detach();
    $this->roles()->attach($id);
  } 

}
//get current role 
public function getRole()
{
  return $this->roles()->first()->name;

}

}
