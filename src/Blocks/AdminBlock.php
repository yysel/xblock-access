<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 19-11-26
 * Time: 下午1:20
 */

namespace XBlock\Access\Blocks;


use XBlock\Access\Dict;
use XBlock\Kernel\Blocks\ModelBlock;
use XBlock\Kernel\Elements\Button;
use XBlock\Kernel\Elements\Field;
use XBlock\Kernel\Elements\Component;

class AdminBlock extends ModelBlock
{

    public $edit_include = ['status'];

    public function component()
    {
        return Component::table()->border();
    }

    public function header()
    {
        return [
            Field::uuid(),
            Field::text('name', '姓名')->writable()->require()->filterable(),
            Field::text('username', '用户名')->addable()->require()->filterable(),
            Field::text('phone', '手机号')->writable()->filterable(),
            $this->getRoleFiled(),
            Field::text('status')->invisible(),
        ];
    }

    protected function getRoleFiled()
    {
        return Field::cascadeCheckboxAllNode('role', '角色')->writable()->dict(Dict::role());
    }

    public function button()
    {
        return [
            Button::add(),
            Button::edit('solid_icon'),
            Button::delete('solid_icon'),
            Button::switchIcon('status', '状态')->inner()->confirm('该操作冻结用户，无法再次登录，是否继续？'),
        ];
    }

    public function beforeAdd($model)
    {
        $user = $model->newQuery()->withoutGlobalScopes()->where('username', $model->username)->orWhere('phone', $model->username)->first();
        if ($user) return modal(false)->info('创建失败，用户名已经存在！');
        $user = $model->newQuery()->withoutGlobalScopes()->where('phone', $model->phone)->first();
        if ($user) return modal(false)->info('创建失败，手机号已经存在！');
    }
}