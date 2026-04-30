<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    // Check if admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}