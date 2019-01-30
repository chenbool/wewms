<?php

namespace app\admin\validate;

use think\Validate;

class Sale extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'sn'           =>  'require|unique:sale',
        'data'           =>  'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'sn.require'       =>  '编号不能为空',
        'sn.unique'        =>  '编号已经存在',
        'data.require'     =>  '请添加产品',
    ];
}
