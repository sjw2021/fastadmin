<?php


namespace app\api\controller;

use app\api\service\JwtService;
use think\App;
use think\exception\HttpResponseException;

class Base extends \app\BaseController
{
    protected $uid = 0;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    // 初始化
    protected function initialize()
    {
        $token = $this->request->header('token', '');
        $userData = JwtService::verifyToken($token);
        if ($userData) $this->uid = $userData['user_id'];
    }

    /**
     * 输出数据
     * @param $code
     * @param string $msg
     * @param null $data
     * @param array $header
     * @return \think\Response
     */
    public function output($code, $msg = '', $data = null, $header = [])
    {
        $data = [
            'code'  => $code,
            'msg'   => $msg,
            'data'  => $data
        ];
        throw new HttpResponseException(response($data, 200, $header, 'json'));
    }

}