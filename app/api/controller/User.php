<?php


namespace app\api\controller;


use app\api\model\UserModel;
use app\api\service\JwtService;
use think\helper\Str;

/**
 * 用户管理
 * Class User
 * @package app\api\controller
 */
class User extends Base
{
    /**
     * 注册
     */
    public function register()
    {
        $username = input('username/s', '');
        $password = input('password/s', '');
        $email = input('email/s', '');
        $salt = Str::random(6);
        $userModel = new UserModel();
        if ($username) {
            $row = $userModel->find(['username' => $username]);
            if ($row) $this->output(1, '用户名已被使用');
        } elseif ($email) {
            $row = $userModel->find(['email' => $email]);
            if ($row) $this->output(1, '邮箱已被使用');
        }
        $userModel->save([
            'username'  => $username,
            'email'     => $email,
            'salt'      => $salt,
            'password'  => md5($password . $salt),
            'status'    => 3
        ]);
        $this->output(0, '注册成功');
    }

    /**
     * 登录
     */
    public function login()
    {
        $username = input('username/s', '');
        $email = input('email/s', '');
        $password = input('password/s', '');
        $userModel = new UserModel();
        $row = [];
        if ($username) {
            $row = $userModel->find(['username' => $username]);
        } elseif ($email) {
            $row = $userModel->find(['email' => $email]);
        } else {
            $this->output(1, '参数错误');
        }
        if (!$row) {
            $this->output(2, '用户不存在');
        }
        if ($row['password'] != md5($password . $row['salt'])) {
            $this->output(3, '密码错误');
        }
        if ($row['status'] != 1) $this->output(4, '用户未审核通过或已被禁用');
        $token = JwtService::genToken(['user_id' => $row['id']]);
        $this->output(0, '登录成功', ['token' => $token]);
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $this->output(0, '成功退出');
    }

    /**
     * 编辑个人信息
     */
    public function edit()
    {
        if ($this->uid <= 0) $this->output(-1, '请登录后操作');
        $this->output(0, '修改成功');
    }

    /**
     * 签到
     */
    public function sign()
    {
        if ($this->uid <= 0) $this->output(-1, '请登录后操作');
        $this->output(0, '签到成功');
    }
}