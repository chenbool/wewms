<?php
namespace app\admin\controller;

use think\captcha\Captcha,
    app\common\controller\Base;

class Login extends Base
{
    /**
     * [__construct 构造方法]
     */
    function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        return $this->object->login();
    } 

    /**
     * [quit 注销登陆]
     */
    public function quit()
    {
        session('admin_user', null);
        return redirect('login/index');
    } 

    // 验证码
	public function verify()
    {
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    24,    
            // 验证码位数
            'length'      =>    4,   
            // 关闭验证码杂点
            'useNoise'    =>    false, 
            'codeSet'     =>    '0123456789'
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();    
    }


    /**
     * [oauth 授权登陆]
     */
    public function oauth()
    {
        return $this->object->oauth();
    }

    /**
     * [bindOauth 绑定授权登陆]
     */
    public function bindOauth()
    {
        return $this->object->bindOauth();
    }


}
