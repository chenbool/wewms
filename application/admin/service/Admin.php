<?php
namespace app\admin\service;
use app\admin\model\Admin as Model,
	app\common\service\Base,
	app\admin\validate\Admin as Validate;

class Admin extends Base
{

	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		$this->model 	= new Model();
		$this->validate = new Validate();
	}

	/**
	 * [index 管理员列表]
	 * @return [json] [分页结果集]
	 */
	public function index()
	{
        if( !request()->isAjax() ) return view();

        // 获取参数
		$param = input('data');
        // $date = explode(' - ',$param['date']);

        // 自定义 where 条件
        $where = [];
        ( isset($param['id']) 		&& $param['id'] )		 && $where[] = [ 'id', $param['id'] ];
        ( isset($param['username']) && $param['username'] )  && $where[] = [ 'username', 'like','%'.$param['username'].'%' ];
		( isset($param['nickname']) && $param['nickname'] )  && $where[] = [ 'nickname', 'like','%'.$param['nickname'].'%' ];
		
		input('keyword') && $where[] = [ 'username|nickname', 'like','%'.input('keyword').'%' ];

        // 日期为空 不赋值
        // $date[0] && $where[] = ['last_time', 'between time', "$date[0],$date[1]" ];
        return $this->buildPage($where);
	}

	/**
	 * [create 添加创建方法]
	 * @return [bool] [结果集]
	 */
	public function create()
	{
		$list = model('adminRole')->where('status','eq',0)->select();
		$list = $this->_tree($list);
		return [
			'list'	=>	$list
		];
	}	

	/**
	 * [edit 编辑页面]
	 * @param  [int] $id 	[id]
	 * @return [array]     	[结果集]
	 */
	public function edit($id)
	{
		$row = $this->model->find($id);
		
		// 查询的时候去除自己
		$list = model('adminRole')->where([
			['status','eq',0],
			['id','neq',$row->id]
		])->select();
		$list = $this->_tree($list);

		return [
			'row'	=>	$row,
			'list'	=>	$list
		];
	}


	/**
	 * [save 保存数据]
	 * @return [json] [结果集]
	 */
	public function save()
	{
		// 获取参数 密码MD5加密
		$param = input('post.');
		$param['password'] = md5( $param['password'] );
		$param['create_time'] = time();

		// 验证数据
		$this->validate = new Validate();
		return $this->_save( $param );
	}

	/**
	 * [update 更新数据]
	 * @return [json] [结果集]
	 */
	public function update(){
		$param = input('post.');
		$param['password'] = md5( $param['password'] );

		// 验证数据
		$this->validate = new Validate();
		return $this->_save( $param, 'update' );
	}

	/**
	 * [delete 删除数据]
	 * @param  [int] $id [id]
	 * @return [bool]    [结果集]
	 */
	public function delete($id)
	{	
		// 判断删除的是否是数组
		is_array($id) || $id = [$id];
		// 判断删除的是不是自己
		if( session('admin_user.id') == $id || in_array( session('admin_user.id') ,$id) ){
			return ['error'	=>	100,'msg'	=>	'不可以删除自己'];
		}

		// 判断是否删除成功
    	if( $this->model->destroy($id) ){
    		return ['error'	=>	0,'msg'	=>	'删除成功'];
    	}else{
    		return ['error'	=>	100,'msg'	=>	'删除失败'];	
    	}
	}

}