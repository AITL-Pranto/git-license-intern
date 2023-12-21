<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    //a particular role has many users
    public function getUser()
    {
        $this->hasMany(User::class, 'admin_role_id');
    }
}
