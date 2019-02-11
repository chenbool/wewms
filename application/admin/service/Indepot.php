<?php
namespace app\admin\service;
use think\Db,
	app\common\service\Base,
	app\admin\service\Stock,
	app\admin\model\Indepot as Model,
	app\admin\validate\Indepot as Validate;

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
			'list'		=>	model('purchase')->where([ 
				'status'=>0,
				// 'state' => [ 'neq',2 ]
			])->select(),
			'supplier'	=>	model('supplier')->where([ 'status'=>0 ])->select()
		];
	}

	/**
	 * [save 保存数据]
	 * @return [json] [结果集]
	 */
	public function save()
	{
		// 监听钩子函数 保存之前
		// Hook::listen('sale_begin');

		$param = input();
		$param['in_time'] = strtotime($param['in_date']);
		$param['create_time'] = time();

		// 验证数据
		$this->validate = new Validate();
		return $this->_save($param);
	}	

	/**
	 * [update 更新数据]
	 * @return [json] [结果集]
	 */
	public function update(){
		$param = input('post.');
		$param['in_time'] = strtotime($param['in_date']);
		// 验证数据
		$this->validate = new Validate();
		return $this->_save( $param, 'update' );
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
		$res = false;
		if( $type=='add' ){
			$res = $this->_validate($data);
		}else{
			$res = $this->_validate($data,'edit');
		}
		if( $res['error'] > 0 ) return $res;

		//检测保存类型
		if( $type == 'add' ){


			// 事务操作
			Db::transaction(function () use($data) {

				$res = $data;
				unset($res['data']);
				unset($res['date']);
				$id = $this->model->insertGetId($res);



				// 组装插入的数据
				$temp = [];
				foreach ($data['data'] as $k => $v) {
					$temp[] = [
						'fid' 			=> $id,
						'pid' 			=>  $v['pid'],
						'sid' 			=> $v['id'],
						'num' 			=> $v['num'],
						'price' 		=> $v['price'],
						'brand'			=>	$v['brand']['id'],
						'color'			=>	$v['color']['id'],
						'unit'			=>	$v['unit']['id'],
						'depot'			=>	$v['depot'],
						'location'		=>	$v['location'],
						'count'			=>	$v['count'],
						'create_time'	=>	time()
					];
				}
				// 插入全部
				model('IndepotMain')->insertAll($temp);

				// 更新订单状态
				model('purchase')->save(
					[ 'state' => 1 ],
					[ 'id'=> $data['purchase'] ] 
				);	

				// 修改仓库库存
				$stock = new Stock();
				$stock->setNum($data['data']);

				return ['error'	=>	0,'msg'	=>	'添加成功' ];
			});

			return ['error'	=>	0,'msg'	=>	'添加成功' ];
		}else{
			
				// 修改仓库库存
				$stock = new Stock();
				$stock->setUpdateNum($data['data'], model('IndepotMain') );
				die;

			// 事务操作
			Db::transaction(function () use($data) {

				//保存全部
				$this->model->update($data);

				// 组装插入的数据
				$temp = $addTemp = [];
				foreach ($data['data'] as $k => $v) {
					// 检测是否有该产品
					if( isset($v['sid']) ){
						$temp[] = [
							'id'			=> $v['id'],	
							'sid' 			=> $v['sid'],
							'pid' 			=> $v['pid'],
							'num' 			=> $v['num'],
							'price' 		=> $v['price'],
							'brand'			=>	$v['brand']['id'],
							'color'			=>	$v['color']['id'],
							'unit'			=>	$v['unit']['id'],
							'depot'			=>	$v['depot'],
							'location'		=>	$v['location'],
							'count'			=>	$v['count'],
						];
					}else{
						$addTemp[] = [
							'fid'			=> $data['id'],
							'sid' 			=> $data['id'],
							'pid' 			=> $v['id'],
							'num' 			=> $v['num'],
							'price' 		=> $v['price'],
							'brand'			=>	$v['brand']['id'],
							'color'			=>	$v['color']['id'],
							'unit'			=>	$v['unit']['id'],
							'depot'			=>	$v['depot'],
							'location'		=>	$v['location'],
							'count'			=>	$v['count'],
							'create_time'	=>	time()
						];
					}

				}
				// 更新
				model('IndepotMain')->saveAll($temp);
				// 新增
				model('IndepotMain')->insertAll($addTemp);

				// 检测是否有删除的数据
				isset($data['dels']) || $data['dels'] =[];
				// 删除
				model('IndepotMain')->destroy($data['dels']);


				return ['error'	=>	0,'msg'	=>	'修改成功' ];
			});

			return ['error'	=>	0,'msg'	=>	'修改成功'];
		}

		// Hook::listen('sale_end');
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

		// 事务操作
		Db::transaction(function () use($id) {
			// 判断是否删除成功
			if( $this->model->destroy($id) ){
				model('IndepotMain')->where([ 'sid'=>$id ])->delete();;	
			}else{
				return ['error'	=>	100,'msg'	=>	'删除失败'];	
			}
		});

		return ['error'	=>	0,'msg'	=>	'删除成功'];
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
		$count = Model::with('supplier')->where($where)->count();

		// 查询
		$data = Model::with('supplier')
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
