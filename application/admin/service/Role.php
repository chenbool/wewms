<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\model\AdminRole as Model,
	app\admin\validate\AdminRole as Validate;


class Role extends Base
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
	 * @return [json] [分页结果集]
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
	 * [update 更新数据]
	 * @return [json] [结果集]
	 */
	// public function update(){
	// 	// 验证数据
	// 	$this->validate = new Validate();
	// 	return $this->_save( input('post.'), 'update' );
	// }

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
		// 获取参数 密码MD5加密
		$param = input('post.');
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

	// 分配权限
	public function distribute()
	{
		$id = input('id');
		$row = $this->model->find($id);

		if( !request()->isPost() ){
			$this->assign([
				'row'	=>	$row
			]);
			return view();
		}else{
			
			$ids = '';
			if( input('?ids') ){
				$ids = implode( ',',input('ids') );
			}

			// 检测是否改变
			if( $row->ids == $ids ){
				return json( ['error'	=>	0,'msg'	=>	'没有改变' ] );				
			}

			$row->ids = $ids;
			
			if( $row->save() ){
				return json( ['error'	=>	0,'msg'	=>	'分配成功' ] );
			}else{
				return json( ['error'	=>	100,'msg'	=>	'分配失败' ] );
			}
		}

	}

	// 获取树结构
	public function getTree()
	{
		$res = model('adminNode')->where('status','eq',0)->select();
		$data = $this->_tree($res);

		// 查询信息
		$row = $this->model->find( input('id') );

		$data = $this->treeToJson($res,$row->ids);
		return json( $data );
	}
	
	//转为json
	public function treeToJson($data,$ids){
		
		$tree = [
			'text'	=>	'全部',
			'id'	=>	0,
			'state'	=>	[
				'opened'	=>	true,
				'selected'	=> true 
			],
			'children'	=>	[]
		];

        foreach($data as $k => $v){

				if( $v->pid == 0 ){
					$tree['children'][] = [
						'text'	=>	$v->name,
						'id'	=>	$v->id,
						// 'icon'	=>	'fa-plus',
						'state'	=>	[
							'selected'	=>	$this->is_checked($v->id,$ids),
							'checked'	=>	$this->is_checked($v->id,$ids)
						]
					];
				}else{
					if( $v->level == 1 ){
						// 遍历节点
						foreach ( $tree['children'] as $kk => $vv) {
							if( $v->pid == $vv['id']){
								
								$tree['children'][$kk]['children'][]=[
									'text'	=>	$v->name,
									'id'	=>	$v->id,
									// 'icon'	=>	'fa-plus',
									'state'	=>	[
										'selected'	=>	$this->is_checked($v->id,$ids),
										'checked'	=>	$this->is_checked($v->id,$ids)
									]
								];
							}
						}

					}elseif ($v->level == 2) {
						// 遍历节点
						foreach ( $tree['children'] as $kk => $vv) {
							// 判断是否有子级
							if( isset($vv['children']) ){
								foreach ($vv['children'] as $ko => $vo) {
									if( $v->pid == $vo['id']){
										$tree['children'][$kk]['children'][$ko]['children'][]=[
											'text'	=>	$v->name,
											'id'	=>	$v->id,
											// 'icon'	=>	'fa-plus'
										];
									}
								}
							}

						}
					}

				}
		}
		
        return $tree;
	}

	// 检测是否选中
	public function is_checked($id,$ids)
	{
		$ids = explode(',',$ids);

		if( in_array($id,$ids) ){
			return true;
		}else{
			return false;
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
		$count = $this->model
		->alias('a')
		->field('a.id')
		->join('__ADMIN_ROLE__ b','a.pid = b.id','LEFT')
		->where($where)
		->count();

		// 查询
		$data = $this->model
		->alias('a')
		->field('a.*,b.name as pname')
		->join('__ADMIN_ROLE__ b','a.pid = b.id','LEFT')
		->where($where)
		->order($sort, $order)
		->select();

		// 无限级分类
		$data = $this->_tree($data);
		return json($data);
	}


}
