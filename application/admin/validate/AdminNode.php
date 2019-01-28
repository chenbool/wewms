<?php
namespace app\admin\validate;
use think\Validate;

class AdminNode extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'  =>  'require|unique:admin_node',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require'  =>  '名称不能为空',
        'name.unique'   =>  '节点名称已经存在',
    ];
}
