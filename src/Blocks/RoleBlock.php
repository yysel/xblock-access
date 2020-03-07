<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 19-11-22
 * Time: 下午4:25
 */

namespace XBlock\Access\Blocks;


use Illuminate\Http\Request;
use XBlock\Access\Dict;
use XBlock\Access\Permission;
use XBlock\Access\Role;
use XBlock\Kernel\Blocks\ModelBlock;
use XBlock\Kernel\Elements\Field;
use XBlock\Kernel\Elements\Button;
use XBlock\Kernel\Elements\Component;

class RoleBlock extends ModelBlock
{
    public $title = '角色管理';
    public $origin = Role::class;

    public function component()
    {
        return Component::table()->border();
    }

    public function header()
    {
        return [
            Field::uuid(),
            Field::text('title', '角色名称')->writable(true),
            Field::textArea('description', '详细描述')->writable(),
            Field::text('children', '子项')->invisible(),
            Field::radio('parent_uuid', '父级')->invisible()->writable()->dict(Dict::role()),
            Field::text('permission', '权限设置')->invisible()->writable()->parent('parent_uuid'),
        ];
    }

    public function button()
    {
        return [
            Button::add(),
            Button::edit('solid_icon'),
            Button::delete('solid_icon'),
        ];
    }

    public function where($query)
    {
        return $query->whereNull('parent_uuid')->when(!user('is_admin'), function ($q) {
            $q->whereIn('uuid', user('role'));
        });
    }

    public function permissionTree(Request $request)
    {
        $data = (new Permission())->getTree($request->uuid);
        return message(true)->data($data);
    }
}
