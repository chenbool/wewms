<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\model\Cate as Model,
	app\admin\validate\Cate as Validate;

/**
 * 作者:bool
 * QQ  :30024167
 */
class Cate extends Base
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
	 * [create 添加创建方法]
	 * @return [bool] [结果集]
	 */
	public function create()
	{
		$list = $this->model->select();
		$list = $this->_tree($list);
		return [
			'list'	=>	$list
		];
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
		// 去除自己
		$list = $this->model->where('id','neq',$id)->select();
		$list = $this->_tree($list);
		return [
			'row'	=>	$this->model->find($id),
			'list'	=>	$list
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

	/**
	 * [buildPage 数据分页筛选查询 ]
	 * @return [json] [结果集]
	 */
	protected function buildPage($where=null)
	{
		// 接受参数
		$pageSize = input('rows/d');
		$page = input('page/d');
		$sort = input('sort/s');
		$order = input('sortOrder/s');

		//总记录数
		$count = Model::with('parent')->where($where)->count();

		// 查询
		$data = Model::with('parent')
		->where($where)
		->order($sort, $order)
		->page("{$page},{$pageSize}")
		->select();

		// 无限级分类
		$data = $this->_tree($data);
		return json($data);
	}

}
