<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 19-11-22
 * Time: 下午4:24
 */

namespace XBlock\Access\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'access_roles';
    protected $appends = ['children'];
    protected $permission_object = [];
    protected $permission_array = [];

    protected $casts = [
        'parent_id' => 'int'
    ];

    public function child()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function getChildrenAttribute()
    {
        $child = $this->child()->when(!user('is_admin'), function ($q) {
            $q->whereIn('id', user('role'));
        })->get();
        return $child->count() ? $child : null;
    }

    public function getPermissionAttribute()
    {
        if ($this->permission_object) return $this->permission_object;
        return $this->permission_object = $this->attributes['permission'] ? json_decode($this->attributes['permission']) : [];
    }

    public function permission()
    {
        if ($this->permission_array) return $this->permission_array;
        return $this->permission_array = collect(json_decode($this->attributes['permission']))->flatten()->all();
    }


}