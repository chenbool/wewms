<?php
namespace app\admin\validate;
use think\Validate;

class Depot extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
    protected $rule = [
        'name'      =>  'require|unique:depot',
        'contact'   =>  'require',
        'phone'     =>  'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message  =   [
        'name.require'      =>  '仓库名称不能为空',
        'name.unique'       =>  '仓库名称已经存在',
        'contact.require'   =>  '联系人不能为空',
        'phone.require'     =>  '手机号不能为空',
    ];
    
}
