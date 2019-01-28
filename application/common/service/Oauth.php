<?php
namespace app\common\service;


/**
 * Oauth 授权操作逻辑处理
 * 作者:bool
 * QQ  :30024167
 */
class Oauth extends Base
{
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		// $this->model = new Model();
	}


	/**
	 * [getOauth 生成授权列表]
	 * @return [type] [description]
	 */
	public function buildOauth()
	{
		$res= model('plug')->where('type','=','oauth')->select();
		foreach ($res as $v) {

			$v['config'] = include env('root_path').$v['path'].'/conf.php';
		}
		return $res;
	}

	/**
	 * [oauth 授权登陆]
	 * @return [type] [结果集]
	 */
	public function oauth(){
		$param = input();
		$type = ucfirst($param['type']);

		$path = env('root_path').'plug/oauth/'.strtolower($type).'/'.$type.'.php';
		file_exists( $path ) || die('插件文件不存在：'.$path);

		// 引入插件
		include $path;

		//实例化类
		$class = "\\plug\\oauth\\{$type}";
		$oauth = new $class();
		// 授权获取用户信息
		return $oauth->get_userinfo($param['code']);
 
	}


}