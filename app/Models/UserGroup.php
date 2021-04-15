<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    //
    public $timestamps = false;
    protected $table = 'user_groups';
    protected $fillable = ['name'];

    const TYPE_ADMIN = 1;
    const TYPE_BUNDA = 2;
    const TYPE_BIDAN = 3;
    const TYPE_FASKES = 4;

    public function roles() {
        return $this->belongsToMany(UserRole::class, 'user_groups_roles', 'user_group_id', 'user_role_id');
    }
}
