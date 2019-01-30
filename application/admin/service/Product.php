<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\model\Product as Model,
	app\admin\validate\Product as Validate;

/**
 * 作者:bool
 * QQ  :30024167
 */
class Product extends Base
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
		$list = model('cate')->select();
		$list = $this->_tree($list);
		return [
			'list'	=>	$list,
			'unit'	=>	model('unit')->where([ 'status'=>0 ])->select(),
			'brand'	=>	model('brand')->where([ 'status'=>0 ])->select(),
			'color'	=>	model('color')->where([ 'status'=>0 ])->select(),
			'supplier'	=>	model('supplier')->where([ 'status'=>0 ])->select(),
			'customer'	=>	model('customer')->where([ 'status'=>0 ])->select(),
			'depot'		=>	model('depot')::with('location')->where([ 'status'=>0 ])->select(),
			// 'location'	=>	model('location')->where([ 'status'=>0 ])->select(),
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
		$list = model('cate')->select();
		$list = $this->_tree($list);
		return [
			'row'	=>	$this->model->find($id),
			'list'	=>	$list,
			'unit'	=>	model('unit')->where([ 'status'=>0 ])->select(),
			'brand'	=>	model('brand')->where([ 'status'=>0 ])->select(),
			'color'	=>	model('color')->where([ 'status'=>0 ])->select(),
			'supplier'	=>	model('supplier')->where([ 'status'=>0 ])->select(),
			'customer'	=>	model('customer')->where([ 'status'=>0 ])->select(),
			'depot'	=>	model('depot')::with('location')->where([ 'status'=>0 ])->select(),
			// 'location'	=>	model('location')->where([ 'status'=>0 ])->select()
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

		// 检查搜索字段 supplier.title
		$res = preg_match('#^[a-z]+\.[a-z]+$#',$sort);
		if( $res ){
			$exp = explode(".",$sort);
			$sort = $exp[0];
		}

		//总记录数
		$count = Model::with('cate')->where($where)->count();

		// 查询
		$data = Model::with('cate,supplier,brand,unit,color')
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

	public function select(){
		$list = model('cate')->select();
		$list = $this->_tree($list);
		
		$this->assign([
			'list'	=>	$list,
			'brand'	=>	model('brand')->where([ 'status'=>0 ])->select(),
			'color'	=>	model('color')->where([ 'status'=>0 ])->select(),
			'depot'	=>	model('depot')::with('location')->where([ 'status'=>0 ])->select()
		]);
		return view();
	}


}
