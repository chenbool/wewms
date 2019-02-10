<?php
namespace app\admin\service;
use app\common\service\Base,
	app\admin\Model\Admin as Model,
	app\admin\validate\Admin as Validate,
	app\common\service\Oauth;

/**
 * Login 登陆操作逻辑处理
 * 作者:bool
 * QQ  :30024167
 */
class Login extends Base
{
	protected $oauth;
	/**
	 * [__construct 构造方法]
	 */
	function __construct()
	{
		parent::__construct();
		$this->model = new Model();
		$this->oauth = new Oauth();
	}

	/**
	 * [index index方法]
	 * @return [bool] [结果集]
	 */
	public function index()
	{
		$this->assign([
			'oauth'	=>	$this->oauth->buildOauth()
		]);
    	return view();
	}

	/**
	 * [login 处理登陆操作]
	 * @return [array] [结果集]
	 */
	public function login()
	{
		if( request()->isPost() ){
			$this->validate = new Validate();	// 创建验证器
			return $this->_validate( input('post.') );
		}
	}

	/**
	 * [_validate 验证数据]
	 * @param  [type] $param [提交的数据]
	 * @return [array]      [结果集]
	 */
	protected function _validate($param,$scene=false)
	{
		// 检测验证码
		// $captcha = input('captcha');
		// if( empty($captcha) ){
		// 	return json( ['error'	=>	100,'msg'	=>	'请输入验证码' ] );
		// }
		// if( !captcha_check($captcha ) ){
		// 	return json( ['error'	=>	100,'msg'	=>	'验证码错误' ] );
		// }

		// 验证提交数据
		if( $this->validate->scene('login')->check( $param) ) {

			// 通过用户名查找
			$res = $this->model->getByUsername( $param['username'] );

			// 判断是否存在
			if( !is_null($res) ){

				// 检测密码是否正确
				if(  $res['password'] == md5( $param['password'] )  ){
					// 保存用户信息
					session('admin_user', $res); 
					$res->last_time = time();
					$res->last_ip = request()->ip();
					$res->save();
					return json( ['error'	=>	0,'msg'		=>	'登陆成功' ] );
				}else{
					return json( ['error'	=>	100,'msg'	=>	'账号或者密码错误' ] );
				}

			}else{
				return json( ['error'	=>	100,'msg'	=>	'用户不存在' ] );
			}
		}else{
			return json( ['error'	=>	100, 'msg'	=>	$this->validate->getError() ] );
		}

	}


	/**
	 * [oauth 授权登陆]
	 * @return [type] [结果集]
	 */
	public function oauth(){
		// 获取授权
		$res = $this->oauth->oauth();
		!$res && $this->error('授权失效');

		$type = input('type');

		// 查询是否存在绑定的用户
		$info = model('admin_oauth')
		->where([ 'open_id'	=>	$res['open_id'] ])
		->find();

		// 数据为空 表示没有绑定
		if( is_null($info) ){

			return view('',[
				'info'	=>	$res,
				'type'	=>	$type
			]);
			// die( "你还没有绑定{$type}!" );
		}

		// 登陆成功 修改 登陆时间 登陆ip
		$user = model('admin')->find($info['uid']);
		session('admin_user', $user); 
		$user->last_time = time();
		$user->last_ip = request()->ip();
		$user->save();

		// 跳转到首页
		// $this->success('登陆成功','index/index');
	}

	/**
	 * [bindOauth 绑定授权登陆]
	 * @return [type] [结果集]
	 */
	public function bindOauth(){
		$param = input('post.');

		// 通过用户名查找
		$res = $this->model->getByUsername( $param['username'] );

		// 判断是否存在
		if( !is_null($res) ){

			// 检测密码是否正确
			if(  $res['password'] == md5( $param['password'] )  ){
				// 查询绑定
				$oauth = model('admin_oauth')->where([
					'open_id'	=>	$param['open_id'],
				])->find();

				// 判断第三方账号否绑定过
				if( !is_null($oauth) ){
					return json( ['error'	=>	100,'msg'	=>	$param['type'].'账号已经绑定过' ] );
				}

				//查询账号有木有绑定过
				$user = model('admin_oauth')->where([
					'uid'	=>	$res->id,
					'type'	=>	$param['type'],
				])->find();

				// 判断账号否绑定过
				if( !is_null($user) ){
					return json( ['error'	=>	100,'msg'	=>	'此账号绑定过'.$param['type'] ] );
				}

				// admin_oauth 表 添加绑定
				model('admin_oauth')->create([
					'uid'		=>	$res['id'],
					'type'		=>	$param['type'],
					'open_id'	=>	$param['open_id'],
					'data'		=>	json_encode($param),
					'url'		=>	$param['url'],
					'add_time'	=>	time()
				]);

				// 绑定成功
				session('admin_user', $res); 

				//更新 admin 表 用户信息
				( is_null($res->head_img) 	|| empty($res->head_img) ) 	&& $res->head_img = $param['head_img'];
				( is_null($res->nickname) 	|| empty($res->nickname) ) 	&& $res->nickname = $param['name'];
				( is_null($res->email) 		|| empty($res->email) ) 	&& $res->email = $param['email'];
				( is_null($res->phone) 		|| empty($res->phone) ) 	&& $res->phone = $param['phone'];
				$res->last_time = time();
				$res->last_ip = request()->ip();
				$res->save();

				return json( ['error'	=>	0,'msg'	=>	'绑定成功' ] );
			}else{
				return json( ['error'	=>	100,'msg'	=>	'密码错误' ] );
			}

		}else{
			return json( ['error'	=>	100,'msg'	=>	'用户不存在' ] );
		}

	}


}