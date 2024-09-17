<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory,HasUlids;
    protected $guarded = [] ; 

    public function roles(){
        return $this->belongsToMany(Role::class,"role_permissions")->withPivot('permission_id')->withTimestamps();
    }

    public function users(){
        return $this->belongsToMany(User::class,"company_users")->withPivot(['status','role_id','id'])->withTimestamps();
    }

    public function companyRoles()
    {
        return $this->belongsToMany(Role::class, "company_users")->withPivot(['status', 'user_id', 'id'])->withTimestamps();
    }

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function technologies(){
        return $this->hasMany(Technology::class);
    }

    public function logs(){
        return $this->hasMany(Log::class);
    }
}

