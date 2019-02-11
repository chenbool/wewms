<?php
namespace app\admin\service;
use think\Db,
	app\common\service\Base,
	app\admin\model\Depot as Model;

class Stock extends Base
{
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		$this->model 	= new Model();

	}

	// 改写仓库库存
    public function setNum($data,$sum=0){
        if( is_array($data) ){
			// 事务操作
			Db::transaction(function () use($data) {

				foreach ($data as $k => $v) {
					// 更新库存
					if(  $v['num'] >0 ){
						// 自增
						model('stock')->where(['pid' => $v['pid']])->setInc('num', $v['num']);
					}else {
						// 自减
						model('stock')->where(['pid' => $v['pid']])->setDec('num', $v['num']);
					}
				}

			});
        }else{

		}
	}
	
	// 更新库存
	public function setUpdateNum($data,$model){
		// 事务操作
		Db::transaction(function () use($data,$model) {
			// 更新库存			
			foreach ($data as $k => $v) {
				$res = $model->find($v['id']);

				// 判断是否修改数量
				if( $res['num'] != $v['num'] ){
					
				}
				// dump( $res['num'] ); dump($v['num']);

			}
		});
	}

	// 删除重置数量
	public function resetNum($fid,$model){
		// 事务操作
		Db::transaction(function () use($fid,$model) {

			$res = $model->where([ 'fid'=>$fid ])->select();
			// 遍历
			foreach ($res as $k => $v) {

				// dump($v);

				// $this->setNum([
				// 	$v['pid'],
				// 	-$v['num']
				// ]);

				model('stock')->where(['pid' => $v['pid']])->setDec('num', $v['num']);
			}

		});
	}



}
