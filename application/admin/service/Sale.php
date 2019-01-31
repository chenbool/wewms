<?php
namespace app\admin\service;
use think\Db,
	app\common\service\Base,
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
		$param['sale_time'] = strtotime($param['sale_date']);
		$param['create_time'] = time();

		// 验证数据
		$this->validate = new Validate();
		$this->_save($param);
	}	

	/**
	 * [create 添加创建方法]
	 * @return [bool] [结果集]
	 */
	public function create()
	{
		return [
			'customer'	=>	model('customer')->where([ 'status'=>0 ])->select()
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
			'customer'	=>	model('customer')->where([ 'status'=>0 ])->select()
		];
	}

/* 	public function getList($id){
		$list = model('SaleMain')
			->alias('a')
			->field('a.*,b.name,b.model,b.spec,c.name as unit,d.name as color')
			->join('product b','a.pid = b.id')
			->join('unit c','b.unit = c.id')
			->join('color d','b.color = d.id')
			->where([ 'sid' => $id ])
			->select();
		return json_encode($list);
	} */


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
						'sid' 			=> $id,
						'pid' 			=> $v['id'],
						'num' 			=> $v['num'],
						'price' 		=> $v['price'],
						'brand'			=>	$v['brand']['id'],
						'color'			=>	$v['color']['id'],
						'unit'			=>	$v['unit']['id'],
						'depot'			=>	$v['depot'],
						'location'		=>	$v['location'],
						'create_time'	=>	time()
					];
				}
				model('SaleMain')->insertAll($temp);

				return ['error'	=>	0,'msg'	=>	'添加成功' ];
			});

			return ['error'	=>	0,'msg'	=>	'添加成功' ];
		}else{

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
							'location'		=>	$v['location']
						];
					}else{
						$addTemp[] = [
							'sid' 			=> $data['id'],
							'pid' 			=> $v['id'],
							'num' 			=> $v['num'],
							'price' 		=> $v['price'],
							'brand'			=>	$v['brand']['id'],
							'color'			=>	$v['color']['id'],
							'unit'			=>	$v['unit']['id'],
							'depot'			=>	$v['depot'],
							'location'		=>	$v['location'],
							'create_time'	=>	time()
						];
					}

				}
				// 更新
				model('SaleMain')->saveAll($temp);
				// 新增
				model('SaleMain')->insertAll($addTemp);

				return ['error'	=>	0,'msg'	=>	'修改成功' ];
			});
			
			// $this->model->update($data);

			return ['error'	=>	0,'msg'	=>	'修改成功'];
		}

		// Hook::listen('sale_begin');
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
				model('SaleMain')->where([ 'sid'=>$id ])->delete();;	
			}else{
				return ['error'	=>	100,'msg'	=>	'删除失败'];	
			}
		});

		return ['error'	=>	0,'msg'	=>	'删除成功'];
	}



}
