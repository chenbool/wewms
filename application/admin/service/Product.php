<?php
namespace app\admin\service;
use think\Db,
	app\common\service\Base,
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
		( isset($param['sn']) && $param['sn'] )  && $where[] = [ 'sn', 'eq',$param['sn'] ];
		( isset($param['cate']) && $param['cate'] )  && $where[] = [ 'cate', 'eq',$param['cate'] ];
		( isset($param['brand']) && $param['brand'] )  && $where[] = [ 'brand', 'eq',$param['brand'] ];
		( isset($param['color']) && $param['color'] )  && $where[] = [ 'color', 'eq',$param['color'] ];
		( isset($param['model']) && $param['model'] )  && $where[] = [ 'model', 'like','%'.$param['model'].'%' ];

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
			'depot'		=>	model('depot')->with('location')->where([ 'status'=>0 ])->select(),
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
			'depot'	=>	model('depot')->with('location')->where([ 'status'=>0 ])->select(),
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
		// Stock
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

	// 选择产品
	public function select(){
		$list = model('cate')->select();
		$list = $this->_tree($list);
		
		$this->assign([
			'list'	=>	$list,
			'brand'	=>	model('brand')->where([ 'status'=>0 ])->select(),
			'color'	=>	model('color')->where([ 'status'=>0 ])->select(),
			'depot'	=>	model('depot')->with('location')->where([ 'status'=>0 ])->select()
		]);
		return view();
	}


    /**
     * [_save 添加/更新数据 ]
     * @param  [array]  $data [添加的数据]
     * @param  [string] $type [默认add update是更新数据]
     * @return [array]       [结果集]
     */
    public function _save($data,$type='add')
    {
    	// 验证数据
		$res = $this->_validate($data);
		if( $res['error'] > 0 ) return $res;

		//检测保存类型
		if( $type == 'add' ){
			$data['create_time'] = time();

			// 添加库存表
			Db::transaction(function () use($data) {

				$id = $this->model->insertGetId($data);

				model('stock')->create([
					'pid'	=>	$id,
					'num'	=>	0,
					'update_time' => time(),
					'create_time' => time()
				]);

			});

			return ['error'	=>	0,'msg'	=>	'添加成功' ];
		}else{
			$this->model->update($data);
			return ['error'	=>	0,'msg'	=>	'修改成功'];
		}
    }


	/**
	 * [delete 删除数据]
	 * @param  [int]  $id  [删除数据唯一id]
	 * @return [array]     [结果集]
	 */
    public function delete($id)
    {
		// 删除库存表
		Db::transaction(function () use($id) {

			if( $this->model->destroy($id) ){
				model('stock')->where([ 'pid'	=>	$id ])->delete();
			}else{
				return ['error'	=>	100,'msg'	=>	'删除失败'];	
			}

		});

		return ['error'	=>	0,'msg'	=>	'删除成功'];
    }	



}
