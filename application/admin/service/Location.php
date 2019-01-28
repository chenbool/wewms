<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\model\Location as Model,
	app\admin\validate\Location as Validate;

/**
 * 作者:bool
 * QQ  :30024167
 */
class Location extends Base
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
		$list = model('depot')->where([ 'status' => 0 ])->select();
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
		$list = model('depot')->where([ 'status' => 0 ])->select();
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

		// 生成where条件
		is_null($where) && $where = $this->buildMap();

		//总记录数
		$count = Model::with('depot')->where($where)->count();

		// 查询
		$data = Model::with('depot')
		->where($where)
		->order($sort, $order)
		->page("{$page},{$pageSize}")
		->select();

		return json([
			// 总记录数
			'total' => $count,
			'rows'	=>	$data
		]);

	}

}
