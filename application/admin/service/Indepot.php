<?php
namespace app\admin\service;
use app\common\service\Base;

/**
 * 作者:bool
 * QQ  :30024167
 */
class Indepot extends Base
{
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		// $this->model 	= new Model();
	}

	/**
	 * [create 添加创建方法]
	 * @return [bool] [结果集]
	 */
	public function create()
	{
		return [
			'supplier'	=>	model('supplier')->where([ 'status'=>0 ])->select(),
			'list'		=>	model('purchase')->where([ 
				'status'=>0,
				'state' => [ 'neq',2 ]
			])->select(),
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
			'row'		=>	$this->model->with([ 
				'list' => ['brand','unit','color','product'],
			])->find($id),
			'supplier'	=>	model('supplier')->where([ 'status'=>0 ])->select()
		];
	}

	
}
