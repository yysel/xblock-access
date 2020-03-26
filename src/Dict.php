<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 19-11-28
 * Time: 下午1:36
 */

namespace XBlock\Access;


class Dict
{
    public static function role()
    {
        $roles = Service::getRoleModel(true)->when(!user('is_admin'), function ($query) {
            $query->whereIn('id', user('role'));
        })->get(['title as text', 'id as value', 'parent_id as parent']);
        if (user('is_admin')) return $roles;
        $role_uuids = $roles->pluck('value')->toArray();
        return $roles->map(function ($item) use ($role_uuids) {
            if ($item->parent && !in_array($item->parent, $role_uuids)) $item->parent = null;
            return $item;
        });
    }
}