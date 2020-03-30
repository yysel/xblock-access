<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 19-11-26
 * Time: 下午1:20
 */

namespace XBlock\Access\Blocks;


use XBlock\Access\AccessField;
use XBlock\Access\Dict;
use XBlock\Kernel\Blocks\ModelBlock;
use XBlock\Kernel\Elements\Button;
use XBlock\Kernel\Elements\Component;

class AdminBlock extends ModelBlock
{
    public $title = '系统用户管理';

    public function boot()
    {
        $this->add_except[] = ['role'];
        $this->edit_except[] = ['role'];
    }

    public function component()
    {
        return Component::table()->border();
    }

    protected function getRoleFiled()
    {
        return AccessField::roleMultiSelect('role', '角色')->writable()->dict(Dict::role());
    }

    public function button()
    {
        return [
            Button::add(),
            Button::edit('solid_icon'),
            Button::delete('solid_icon'),
        ];
    }

    public function beforeEdit($model)
    {
        $model->roles()->sync(request('role'));
    }

    public function beforeAdd($model)
    {
        $model->roles()->sync(request('role'));
    }
}