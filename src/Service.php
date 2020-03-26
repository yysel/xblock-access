<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 20-3-24
 * Time: 下午7:13
 */

namespace XBlock\Access;

use XBlock\Access\Models\Role;

class Service
{
    static public function getUserModel($new = false)
    {
        $provider = config('auth.guards.api.provider', 'xblock');
        $model = config('auth.providers.' . $provider . '.model', \App\User::class);
        return $new ? new $model : $model;
    }

    static public function getRoleModel($new = false)
    {
        $model = config('xblock.access.role', Role::class);
        return $new ? new $model : $model;
    }

    static public function getUserRoleTableName()
    {
        return config('xblock.access.user_role', 'access_user_role');
    }

    static public function loadMigrartion()
    {
        app()->afterResolving('migrator', function ($migrator) {
            $migrator->path(__DIR__ . '/../migrations');
        });
    }

}