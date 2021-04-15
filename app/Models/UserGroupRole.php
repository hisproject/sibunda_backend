<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroupRole extends Model
{
    //
    protected $table = 'user_groups_roles';
    protected $fillable = ['user_group_id', 'user_role_id'];
}
