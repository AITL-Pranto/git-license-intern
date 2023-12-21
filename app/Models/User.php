<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRole()
    {
        return $this->belongsTo(Model\Role::class, 'admin_role_id');
    }
    public static function getUserName($user_id = '')
    {
        $username = '';

        if (!empty($user_id)) {
            $user = User::find($user_id);
            $username = $user->username;
        }

        return $username;
    }
    public static function hasPermission($user, $code)
    {
        $isPermission = 0;
        if ($user != null) {
            $isPermission = AdminRolePermission::join('admin_permissions', 'admin_permissions.id', '=', 'admin_role_permissions.admin_permission_id')
                ->where('admin_role_permissions.admin_role_id', $user->admin_role_id)
                ->where('admin_permissions.code', trim($code))
                ->exists();
        }

        return $isPermission;
    }
    public function roleName()
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }
}
