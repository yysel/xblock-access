<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 19-11-22
 * Time: 下午4:25
 */

namespace XBlock\Access\Blocks;


use Illuminate\Http\Request;
use XBlock\Access\AccessField;
use XBlock\Access\Dict;
use XBlock\Access\Permission;
use XBlock\Access\Service;
use XBlock\Kernel\Blocks\ModelBlock;
use XBlock\Kernel\Elements\Field;
use XBlock\Kernel\Elements\Button;
use XBlock\Kernel\Elements\Component;

class RoleBlock extends ModelBlock
{
    public $title = '角色管理';

    public $add_except = ['permission'];

    public function boot()
    {
        $this->origin = Service::getRoleModel();
    }

    public function component()
    {
        return Component::table()->border();
    }

    public function header()
    {
        return [
            Field::key(),
            Field::text('title', '角色名称')->writable(true),
            Field::textArea('description', '详细描述')->writable(),
            Field::text('children', '子项')->invisible(),
            AccessField::roleSelect( 'parent_id', '父级')->invisible()->writable()->dict(Dict::role()),
            AccessField::permissionSelect( 'permission', '权限设置')->invisible()->writable()->parent('parent_id'),
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

    public function beforeAdd($model)
    {
        $model->permission = json_encode(request('permission'));
    }

    public function beforeEdit($model)
    {
        $model->permission = json_encode(request('permission'));
    }

    public function where($query)
    {
        return $query->whereNull('parent_id')->when(!user('is_admin'), function ($q) {
            $q->whereIn('id', user('role'));
        });
    }

    public function permissionTree(Request $request)
    {
        $data = (new Permission())->getTree($request->id);
        return message(true)->data($data);
    }
}
