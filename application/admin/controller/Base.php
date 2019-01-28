<?php
namespace app\admin\controller;

use app\common\controller\Base as Controller;

class Base extends Controller
{
    /**
     * [__construct 构造方法]
     */
	function __construct()
    {
        parent::__construct();

        //检测用户是否登陆
        is_null(session('admin_user')) && die( $this->redirect('login/index') );
        // 不是超级管理员就验证权限
        session('admin_user.id') != 1 && $this->auth();

        // 模板赋值
        $this->assign([
            'admin_user'    =>  session('admin_user'),
        ]);

	}

    protected function auth()
    {
        $role = model('adminRole')->find( session('admin_user.role_id') );
        // cache('role', $role, 10);

        // 设置查询条件
        $where[] = ['id','in',$role->ids];
        // 检测当前控制器 当前方法是否在节点里面
        $where[] = ['controller','=',request()->controller() ];
        $where[] = ['action','=',request()->action() ];
        // 查询是否存在此节点
        $node = model('adminNode')->field('id,controller,action')->where($where)->find();
        $node || die( $this->error('你没有权限访问此页面','index/index') );
    }


}
