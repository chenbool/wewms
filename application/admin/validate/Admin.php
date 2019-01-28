<?php
namespace app\admin\validate;
use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
    protected $rule = [
        'username'  =>  'require|max:16|min:4|unique:admin',
        // 'username'  =>  'require|max:16|min:4',
        'password'  =>  'require|min:4',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message  =   [
        'username.require'  =>  '用户名不能为空',
        'username.min'      =>  '用户名不能小于4位',
        'username.max'      =>  '用户名不能大于16位',
        'username.unique'   =>  '用户名已经存在',
        'password.require'  =>  '密码不能为空',
        'password.min'      =>  '密码不能小于4位',
    ];

    protected $scene = [
        'login'  =>  ['username.require','password'],
    ];

}
