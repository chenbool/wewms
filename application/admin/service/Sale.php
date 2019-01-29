<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\model\Sale as Model,
	app\admin\validate\Sale as Validate;

use think\facade\Hook;

/**
 * 作者:bool
 * QQ  :30024167
 */
class Sale extends Base
{
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		$this->model 	= new Model();
	}
	
	/**
	 * [index 首页]
	 * @return [type] [结果集]
	 */
	public function index()
	{
		if( !request()->isAjax() ) return view();
		// 获取参数
		$param = input('data');
		$where = [];
		( isset($param['name']) && $param['name'] )  && $where[] = [ 'name', 'like','%'.$param['name'].'%' ];		
		input('keyword') && $where[] = [ 'name', 'like','%'.input('keyword').'%' ];
		return $this->buildPage($where);
	}


	/**
	 * [save 保存数据]
	 * @return [json] [结果集]
	 */
	public function save()
	{
		// 监听钩子函数 保存之前
		Hook::listen('sale_begin');

		$param = input();
		$param['create_time'] = time();

		// 验证数据
		$this->validate = new Validate();
		return $this->_save( $param );
	}	

	/**
	 * [create 添加创建方法]
	 * @return [bool] [结果集]
	 */
	public function create()
	{
		return [
			'supplier'	=>	model('supplier')->where([ 'status'=>0 ])->select()
		];
	}

	/**
	 * [edit 编辑页面]
	 * @param  [int] $id 	[id]
	 * @return [array]     	[结果集]
	 */
	public function edit($id)
	{
		return [
			'row'	=>	$this->model->find($id),
			'supplier'	=>	model('supplier')->where([ 'status'=>0 ])->select()
		];
	}

	/**
	 * [update 更新数据]
	 * @return [json] [结果集]
	 */
	public function update(){
		$param = input('post.');
		// 验证数据
		$this->validate = new Validate();
		return $this->_save( $param, 'update' );
	}

}
