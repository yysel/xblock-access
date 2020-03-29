<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 20-3-30
 * Time: 上午12:26
 */

namespace XBlock\Access;


use XBlock\Kernel\Elements\Field;
use XBlock\Kernel\Elements\Fields\BaseField;

class  AccessField
{
    static public function roleSelect($index, $title): BaseField
    {
        return Field::make('role_select', $index, $title);
    }

    static public function roleMultiSelect($index, $title): BaseField
    {
        return Field::make('role_multi_select', $index, $title);
    }

    static public function permissionSelect($index, $title): BaseField
    {
        return Field::make('permission_select', $index, $title);
    }
}