<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 20-3-24
 * Time: 下午6:51
 */

namespace XBlock\Access;


trait AccessAble
{
    protected $access_role_array = [];
    protected $access_permission_array = [];

    public function roles()
    {
        return $this->belongsToMany(Service::getRoleModel(), Service::getUserRoleTableName(), 'user_id', 'role_id');
    }

    public function getRoleAttribute(): array
    {
        if ($this->access_role_array) return $this->access_role_array;
        return $this->access_role_array = $this->roles->pluck('id')->toArray();
    }

    public function getPermissionAttribute(): array
    {
        if ($this->access_permission_array) return $this->access_permission_array;
        $roles = $this->role;
        $role_model = Service::getRoleModel(true);
        return $this->access_permission_array = $role_model->whereIn('id', $roles)->pluck('permission')->flatten()->unique()->values()->toArray();
    }

}