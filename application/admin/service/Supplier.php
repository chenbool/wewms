<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\model\Supplier as Model,
	app\admin\validate\Supplier as Validate;


class Supplier extends Base
{
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		$this->model 	= new Model();
		// $this->validate = new Validate();
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

		// 自定义 where 条件
		$where = [];
		( isset($param['title']) && $param['title'] )  && $where[] = [ 'title', 'like','%'.$param['title'].'%' ];
		( isset($param['email']) && $param['email'] )  && $where[] = [ 'email', 'like','%'.$param['email'].'%' ];
		( isset($param['phone']) && $param['phone'] )  && $where[] = [ 'phone', 'like','%'.$param['phone'].'%' ];
		input('keyword') && $where[] = [ 'title|contact', 'like','%'.input('keyword').'%' ];
        return $this->buildPage( $where );
	}

	/**
	 * [save 保存数据]
	 * @return [json] [结果集]
	 */
	public function save()
	{
		$param = input();
		$param['create_time'] = time();

		// 验证数据
		$this->validate = new Validate();
		return $this->_save( $param );
	}	

	/**
	 * [edit 编辑页面]
	 * @param  [int] $id 	[id]
	 * @return [array]     	[结果集]
	 */
	public function edit($id)
	{
		return [
			'row'	=>	$this->model->find($id)
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
