<?php

namespace app\admin\validate;

use think\Validate;

class Supplier extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'contact'           =>  'require|unique:customer',
        'title'             =>  'unique:customer',
        'tel'               =>  'unique:customer',
        'fax'               =>  'unique:customer',
        'phone'             =>  'require|unique:customer',
        'email'             =>  'require|unique:customer',
        'credit_id'         =>  'unique:customer',
        'taxpayer_id'       =>  'unique:customer',
        'bank_number'       =>  'unique:customer',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'contact.require'       =>  '联系人不能为空',
        'contact.unique'        =>  '联系人已经存在',
        'title.unique'          =>  '公司名称已经存在',
        'tel.unique'            =>  '公司电话已经存在',
        'fax.unique'            =>  '公司传真已经存在',
        'phone.require'         =>  '手机号不能为空',
        'phone.unique'          =>  '手机号已经存在',
        'email.require'         =>  '邮箱不能为空',
        'email.unique'          =>  '邮箱已经存在',
        'credit_id.unique'      =>  '社会信用代码已经存在',
        'taxpayer_id.unique'    =>  '纳税人识别号已经存在',
        'bank_number.unique'    =>  '开户银行卡号已经存在',
    ];
}
