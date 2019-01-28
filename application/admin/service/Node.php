<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\model\AdminNode as Model,
	app\admin\validate\AdminNode as Validate;


class Node extends Base
{
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		$this->model 	= new Model();
		$this->validate = new Validate();
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
	 * [edit 编辑页面]
	 * @param  [int] $id 	[id]
	 * @return [array]     	[结果集]
	 */
	public function edit($id)
	{
		$row = $this->model->find($id);
		// 查询的时候去除自己
		$list = $this->model->where('id','neq',$row->id)->select();
		$list = $this->_tree($list);

		return [
			'row'	=>	$row,
			'list'	=>	$list
		];
	}

	/**
	 * [delete 删除数据]
	 * @param  [int]  $id  [删除数据唯一id]
	 * @return [array]     [结果集]
	 */
    public function delete($id)
    {
		$res = $this->model->where('pid','in',$id)->find();

		if( $res ){
			return ['error'	=>	0,'msg'	=>	'请先删除子级分类'];
			exit;
		}
		
    	if( $this->model->destroy($id) ){
    		return ['error'	=>	0,'msg'	=>	'删除成功'];
    	}else{
    		return ['error'	=>	100,'msg'	=>	'删除失败'];	
    	}
    }

	/**
	 * [buildPage 数据分页筛选查询 ]
	 * @return [json] [结果集]
	 */
	protected function buildPage($where=null)
	{
		// 接受参数
		$sort = input('sort/s');
		$order = input('sortOrder/s');

		//总记录数
		// $count = $this->model
		// ->alias('a')
		// ->field('a.id')
		// ->join('__ADMIN_NODE__ b','a.pid = b.id','LEFT')
		// ->where($where)
		// ->count();

		// // 查询
		// $data = $this->model
		// ->alias('a')
		// ->field('a.*,b.name as pname')
		// ->join('__ADMIN_NODE__ b','a.pid = b.id','LEFT')
		// ->where($where)
		// ->order($sort, $order)
		// ->select();

		//总记录数
		$count = Model::with('parent')->where($where)->count();

		// 查询
		$data = Model::with('parent')
		->where($where)
		->order($sort, $order)
		->select();

		// 无限级分类
		$data = $this->_tree($data);
		return json($data);
	}


}
