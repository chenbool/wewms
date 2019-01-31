<?php
namespace app\common\service;
use think\Controller;

/**
 * Base
 * 作者:bool
 * QQ  :30024167
 */
class Base extends Controller
{
	
	protected $model;		// 模型
	protected $validate;	// 验证器

	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * [delete 删除数据]
	 * @param  [int]  $id  [删除数据唯一id]
	 * @return [array]     [结果集]
	 */
    public function delete($id)
    {
    	if( $this->model->destroy($id) ){
    		return ['error'	=>	0,'msg'	=>	'删除成功'];
    	}else{
    		return ['error'	=>	100,'msg'	=>	'删除失败'];	
    	}
    }


	/**
	 * [index index方法]
	 * @return [bool] [结果集]
	 */
	public function index()
	{
    	return view();
	}

	/**
	 * [create 添加创建方法]
	 * @return [bool] [结果集]
	 */
	public function create()
	{
    	return false;
	}	

	/**
	 * [index save方法]
	 * @return [bool] [结果集]
	 */
	public function save()
	{
		// 验证数据
		return $this->_save( input('post.') );
	}


	/**
	 * [edit 编辑页面]
	 * @param  [int] $id 	[id]
	 * @return [array]     	[结果集]
	 */
	public function edit($id)
	{
		return [
			'row'	=>	$this->model->find($id)
		];
	}

	/**
	 * [update 更新数据]
	 * @return [bool] [结果集]
	 */
	public function update()
	{
		return $this->_save( input('post.'),'update' );
	}


    /**
     * [_validate 数据验证并添加]
     * @param  [array] $req [要验证的数据]
     * @return [array]      [结果集]
     */
    protected function _validate($req,$scene=false)
    {
		$res = false;
		if($scene){
			$res = $this->validate->scene($scene)->check( $req );
		}else{
			$res = $this->validate->check( $req );
		}
		
		// 验证
		if( $res ) {
			return ['error'	=>	0,'msg'	=> '验证成功' ];
		}else{
			return ['error'	=>	100,'msg'	=>	$this->validate->getError() ];
		}
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
			$data['add_time'] = time();
			$this->model->create($data);
			return ['error'	=>	0,'msg'	=>	'添加成功' ];
		}else{
			$this->model->update($data);
			return ['error'	=>	0,'msg'	=>	'修改成功'];
		}
    }


	/**
	 * [uploadFile 上传文件处理]
	 * @param  [string] $name [上传文件名]
	 * @return [string]       [上传路径]
	 */
	protected function uploadFile($name)
	{
		// 获取上传文件
		$file = request()->file($name);
	    if( $file ){
	        $info = $file
		        	// ->validate( config('upload') )
					->move('./uploads');
			if( $info ){
				return [
					'error'	=>	0,
					'msg'	=>	$info->getSaveName()
				];
			}else{
				return [
					'error'	=>	100,
					'msg'	=>	$file->getError()
				];
			}
	        // return $info ? $info->getSaveName() : $this->error( $file->getError() );
	    }
	}


	/**
	 * [buildMap 生成where查询条件]
	 * @return [array] [结果集]
	 */
	protected function buildMap()
	{
		// 接受参数
		$param = input('data');

		// 拼装 where 条件
		$where = [];
		foreach ($param as $k => $v) {
			if( !empty($v)){
				$where[] = [$k,'like',"%{$v}%"];
			}
		}
		return $where;
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
		$count = $this->model->where($where)->count();

		// 查询
		$data = $this->model
		->where($where)
		->order($sort, $order)
		->page("{$page},{$pageSize}")
		->select();

		return json([
			// 总记录数
			'total' => $count,
			'rows'	=>	$data,
			'sql'	=>	$this->model->getLastSql()
		]);
	}

	// 无限级分类
	protected function _tree($data,$pid=0,$level=0){
        static $tree = [];
        foreach($data as $v){
            if( $v['pid']==$pid ){
                $v['level']=$level;
                $tree[]=$v;
                $this->_tree($data,$v['id'],$level+1);
            }
        }
        return $tree;
	}
}
