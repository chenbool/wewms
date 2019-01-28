<?php
namespace app\admin\service;
use app\common\service\Base,
	app\common\model\Plug as Model;

/**
 * Plug 插件逻辑处理
 * 作者:bool
 * QQ  :30024167
 */
class Plug extends Base
{
    protected $path;
    
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		$this->model 	= new Model();
        $this->path = str_replace('\\','/', env('root_path').'plug/' );
	}

	/**
	 * [index 插件首页]
	 * @return [array] [结果集]
	 */
	public function index()
	{	
		//获取已经安装的插件
		$install = $this->model->field('group_concat(code) as install')->find();
		$this->assign([
			'list'		=>	$this->getList(),
			'install'	=>	explode(',',$install['install'])
		]);

		return view();
	}
	
	/**
	 * [conf 插件配置]
	 * @return [type] [结果集]
	 */
	public function conf()
	{
		// 检测是否post
		if( request()->isPost() ){
			return $this->update_conf();
			exit;
		}

		$path = input('get.path');
		if( !file_exists( $path.'/conf.php' ) ){
			return json(['error' =>	100,'msg' => '不存在配置文件' ]);
			exit;
		}

		$this->assign([
			'conf'	=>	include $path.'/conf.php',
			'path'	=>	$path
		]);
		return view();
	}

	protected function update_conf()
	{
		$data = input();
		$path = $data['path'].'/conf.php';
		unset($data['path']);
        //写出配置
        file_put_contents($path,'<?php return '.var_export($data,true).';' );
        return json( ['error'	=>	0,'msg'	=>	'修改成功' ] );
	}

	/**
	 * [install 安装插件]
	 * @return [json] [结果集]
	 */
	public function install()
	{
		$path = input('post.path');

		// 获取配置
		$temp = $this->jsonToArray($path);

		// 检测是否已经安装过
		$res = $this->model->getByCode($temp['code']);
		if( !is_null($res) ){
			return json( ['error'	=>	100,'msg'	=>	'已经安装过此应用' ] );
		}

		// 安装写入数据库
		$res = $this->model->create([
			'name'			=>	$temp['name'],
			'code'			=>	$temp['code'],
			'author'		=>	$temp['author'],
			'path'			=>	$path,
			'type'			=>	$temp['dir'],
			'status'		=>	1,
			'create_time'	=>	time()
		]);	
		// 判断数据库是否执行成功
		if( $res ){
			return json( ['error'	=>	0,'msg'	=>	'安装成功' ] );
		}else{
			return json( ['error'	=>	100,'msg'	=>	'安装失败' ] );
		}
	}

	/**
	 * [install 卸载插件]
	 * @return [json] [结果集]
	 */
	public function uninstall()
	{
		$path = input('post.path');

		// 获取配置
		$temp = $this->jsonToArray($path);

		// 检测是否已经卸载过
		$res = $this->model->getByCode($temp['code']);
		if( is_null($res) ){
			return json( ['error'	=>	100,'msg'	=>	'已经卸载过此应用' ] );
		}

		// 判断数据库是否卸载成功
		if( $res->delete() ){
			return json( ['error'	=>	0,'msg'	=>	'卸载成功' ] );
		}else{
			return json( ['error'	=>	100,'msg'	=>	'卸载失败' ] );
		}
	}

	/**
	 * [plugList 插件列表]
	 * @return [array] [结果集]
	 */
	protected function plugList()
	{
		$plug = [];
		// 获取插件
		$dir = $this->getDir( $this->path );
		// 遍历取插件列表
		foreach ($dir as $v) {
			$plug[$v] = $this->getPlug($this->path.$v.'/');
		}
		return $plug;
	}

	/**
	 * [getList 全部插件]
	 * @return [array] [结果集]
	 */
	protected function getList()
	{

		$plug = [];
		// 获取插件
		$dir = $this->getDir( $this->path);
		// 遍历取插件列表
		foreach ($dir as $k => $v) {
			// $plug[$k] = $this->getPlug('../plug/'.$v.'/');
			$res = $this->getPlug( $this->path.$v.'/');
			$plug = array_merge($plug,$res);
		}
		return $plug;
	}


	/**
	 * [getPlug 获取插件信息]
	 * @param  [string] $path [路径]
	 * @return [array] [结果集]
	 */
	protected function getPlug($path)
	{
		$temp = [];
		$dir = $this->getDir($path);

		// 遍历获取插件
		foreach ($dir as $v) {
			$temp[$v] = $this->jsonToArray($path.$v);
			$temp[$v]['path']	= $path.$v;
		}
		return $temp;
	}

	/**
	 * [getDir 获取目录列表]
	 * @param  [string] $path [路径]
	 * @return [array]       [结果集]
	 */
	protected function getDir($path){
		$dir = scandir($path);
		unset($dir[0]);
		unset($dir[1]);
		return $dir;
	}

	/**
	 * [jsonToArray json转数组]
	 * @param  [string] $path [路径]
	 * @return [type] [description]
	 */
	protected function jsonToArray($path)
	{
		$app_json = file_get_contents($path.'/app.json');
		return json_decode($app_json,true);
	}


}
