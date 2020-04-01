<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 19-11-22
 * Time: 下午4:25
 */

namespace XBlock\Access\Blocks;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use XBlock\Access\AccessField;
use XBlock\Access\Dict;
use XBlock\Access\Service;
use XBlock\Kernel\Blocks\ModelBlock;
use XBlock\Kernel\Elements\ActionCreator;
use XBlock\Kernel\Elements\Component;
use XBlock\Kernel\Elements\FieldCreator;
use XBlock\Kernel\Services\PermissionService;

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

    public function fields(FieldCreator $creator)
    {
        $creator->key();
        $creator->text('title', '角色名称')->writable(true);
        $creator->textArea('description', '详细描述')->writable();
        $creator->text('children', '子项')->invisible();
        $creator->create(AccessField::roleSelect('parent_id', '父级')->invisible()->writable()->dict(Dict::role()));
        $creator->create(AccessField::permissionSelect('permission', '权限设置')->invisible()->writable()->parent('parent_id'));
    }

    public function actions(ActionCreator $creator)
    {
        $creator->add();
        $creator->edit('solid_icon');
        $creator->delete('solid_icon');
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
        $data = (new PermissionService())->getPermissionTree(function (Collection $lists) use ($request) {
            $role = Service::getRoleModel(true)->where('id', $request->id)->first();
            if ($role) {
                $permission = $role->permission();
                return $lists->filter(function ($item) use ($permission) {
                    return in_array($item['value'], $permission) || $item['type'] == 'block';
                });
            } elseif (!user('is_admin')) {
                $permission = user('permission', []);
                return $lists->filter(function ($item) use ($permission) {
                    return in_array($item['value'], $permission) || $item['type'] == 'block';
                });
            }
            return $lists;
        });
        return message(true)->data($data);
    }
}
